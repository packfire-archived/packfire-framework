<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application;

use Packfire\Database\ConnectorFactory;
use Packfire\Config\Framework\Loader as ConfigLoader;
use Packfire\Event\EventHandler;
use Packfire\FuelBlade\ConsumerInterface;
use Packfire\Core\ShutdownTaskManager;
use Packfire\Exception\ServiceException;

/**
 * Application service bucket that loads the application's core services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application
 * @since 1.0-sofia
 */
class ServiceLoader implements ConsumerInterface
{

    /**
     * Invoke the service loader with the IoC container
     * @param \Packfire\FuelBlade\Container $c The IoC container
     * @since 2.1.0
     */
    public function __invoke($c)
    {
        $c['config'] = $c->share(
            function () {
                $config = new ConfigLoader('app');
                return $config->load();
            }
        );
        $config = $c['config'];

        $c['config.ioc'] = $c->share(
            function () {
                $config = new ConfigLoader('ioc');
                return $config->load();
            }
        );
        $iocConfig = $c['config.ioc'];
        if ($iocConfig) {
            $services = $iocConfig->get();
            foreach ($services as $key => $service) {
                if (isset($service['class'])) {
                    $package = $service['class'];
                    $params = isset($service['parameters']) ? $service['parameters'] : null;
                    $c[$key] = $c->share(
                        function ($c) use ($package, $params) {
                            if (class_exists($package)) {
                                $reflect = new \ReflectionClass($package);
                                if ($params) {
                                    $instance = $reflect->newInstanceArgs($params);
                                } else {
                                    $instance = $reflect->newInstance();
                                }
                                if ($instance instanceof ConsumerInterface) {
                                    return $instance($c);
                                } else {
                                    return $instance;
                                }
                            }
                        }
                    );
                } else {
                    throw new ServiceException(
                        'Service "' . $key
                        . '" defined in the service configuration'
                        . ' file "ioc.yml" contains not conain a'
                        . ' class definition.'
                    );
                }
            }
        }

        if ($config) {
            $databaseConf = $config->get('database');
            if ($databaseConf) {
                foreach ($databaseConf as $key => $db) {
                    $package = ($key == 'default' ? '' : '.' . $key);
                    $c['database' . $package . '.driver'] = ConnectorFactory::create($db);
                    $c['database' . $package] = $c->share(
                        function ($c) use ($package) {
                            return $c['database' . $package . '.driver']->database();
                        }
                    );
                }
            }
        }

        $c['events'] = $c->share(
            function ($c) {
                return new EventHandler($c);
            }
        );

        $c['shutdown'] = $c->share(
            function ($c) {
                return new ShutdownTaskManager();
            }
        );

        // load services from ioc.yml

        if (isset($c['cache'])) {
            $shutdown = $c['shutdown'];
            /* @var $shutdown \Packfire\Core\ShutdownTaskManager */
            $shutdown->add('cache.gc', array($c['cache'], 'garbageCollect'));
        }

        return $this;
    }
}
