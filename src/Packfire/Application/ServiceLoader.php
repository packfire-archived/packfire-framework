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
use Packfire\FuelBlade\ServiceLoader as FuelBladeLoader;
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
            FuelBladeLoader::load($c, $iocConfig->get('services'));
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
