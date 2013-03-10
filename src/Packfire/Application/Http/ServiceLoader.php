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
use Packfire\FuelBlade\IConsumer;
use Packfire\Route\Http\Router;
use Packfire\Config\Framework\HttpRouterConfig;
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
class ServiceLoader implements IConsumer {
    
    public function __invoke($c) {
        if(!isset($c['exception.handler'])){
            $c['exception.handler'] = $c->share(function(){
                return new HttpHandler();
            });
        }
        
        $c['config.router'] = $c->share(function(){
            $config = new HttpRouterConfig();
            return $config->load();
        });
        $c['router'] = $c->share(function(){
            return new Router();
        });
        
        if(isset($c['config'])){
            $c['debugger'] = $c->share(function(){
                return new Debugger();
            });
            
            if($c['config']->get('session','enabled')){
                $loader = new SessionLoader();
                $loader($c);
            }
        }
        
        // class finder and loader
        $loadClassFinder = function(){
            $classFinder = new ClassFinder();
            $classFinder->addPath('pack/src/');
            return $classFinder;
        };
        
        if(isset($c['cache'])){
            // only load CacheClassFinder if the cache component is available
            $c['autoload.finder'] = $loadClassFinder;
            $c['autoload.finder'] = $c->share(function(){
                return new CacheClassFinder('src.class.');
            });
        }else{
            $c['autoload.finder'] = $c->share($loadClassFinder);
        }
        
        $c['autoload.loader'] = $c->share(function(){
            return new ClassLoader();
        });
        $c['autoload.loader']->register();
    }
    
}