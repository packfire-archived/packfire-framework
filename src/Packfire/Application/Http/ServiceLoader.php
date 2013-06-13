<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application\Http;

use Packfire\Exception\Handler\HttpHandler;
use Packfire\FuelBlade\ConsumerInterface;
use Packfire\Route\Http\Router;
use Packfire\Config\Framework\Loader as ConfigLoader;
use Packfire\Session\Loader as SessionLoader;
use Packfire\Debugger\Debugger;
use Packfire\Core\ClassLoader\ClassFinder;
use Packfire\Core\ClassLoader\ClassLoader;
use Packfire\Core\ClassLoader\CacheClassFinder;

/**
 * HTTP Application Service Loader
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 2.1.0
 */
class ServiceLoader implements ConsumerInterface
{
    
    public function __invoke($c)
    {
        if (!isset($c['exception.handler'])) {
            $c['exception.handler'] = new HttpHandler();
        }

        $c['config.routes'] = $c->share(
            function ($c) {
                $config = new ConfigLoader($c['config']->get('router', 'routes'));
                return $config->load();
            }
        );
        $c['router'] = new Router();

        if (isset($c['config'])) {
            $debugger = new Debugger();
            $debugger($c);
            $c['debugger'] = $debugger;

            if ($c['config']->get('session', 'enabled')) {
                $loader = new SessionLoader();
                $loader($c);
            }
        }

        // class finder and loader
        $loadClassFinder = function () {
            $classFinder = new ClassFinder();
            $classFinder->addPath(__APP_ROOT__ . '/src/');

            return $classFinder;
        };

        if (isset($c['cache'])) {
            // only load CacheClassFinder if the cache component is available
            $c['autoload.finder'] = $loadClassFinder;
            $c['autoload.finder'] = new CacheClassFinder($c['autoload.finder'], 'src.class.');
        } else {
            $c['autoload.finder'] = $c->share($loadClassFinder);
        }

        $c['autoload.loader'] = new ClassLoader();
        $c['autoload.loader']->register();

        return $this;
    }
}
