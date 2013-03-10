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

use Packfire\Application\ServiceApplication;
use Packfire\Application\Http\Response;
use Packfire\Application\Http\ServiceLoader;
use Packfire\Exception\HttpException;
use Packfire\Exception\MissingDependencyException;
use Packfire\Controller\Invoker as ControllerInvoker;
use Packfire\Response\RedirectResponse;
use Packfire\Route\Http\RedirectRoute;
use Packfire\Net\Http\Method as HttpMethod;
use Packfire\DateTime\TimeSpan;
use Packfire\Collection\Map;
use Packfire\Route\Http\Route;

/**
 * The default web serving application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Http
 * @since 1.0-elenor
 */
class Application extends ServiceApplication {
    
    /**
     * Perform service loading processing
     * @param \Packfire\FuelBlade\Container $container
     * @since 2.1.0
     */
    public function __invoke($container){
        parent::__invoke($container);
        
        $loader = new ServiceLoader();
        $loader($this->ioc);
        return $this;
    }
    
    /**
     * Process a request and prepare the response
     * @since 2.1.0
     */
    public function process(){
        $request = $this->ioc['request'];
        $oriMethod = $request->method();
        if($request->headers()->keyExists('X-HTTP-Method')){
            $oriMethod = $request->headers()->get('X-HTTP-Method');
        }
        if($request->headers()->keyExists('X-HTTP-Method-Override')){
            $oriMethod = $request->headers()->get('X-HTTP-Method-Override');
        }
        $request->method($oriMethod);
        
        if(!isset($this->ioc['router'])){
            throw new MissingDependencyException('Router service required, but missing.');
            return;
        }
        $router = $this->ioc['router'];
        /* @var $router \Packfire\Route\Router */
        $router->load();
        
        $debugMode = isset($this->ioc['config'])
                && $this->ioc['config']->get('app', 'debug');
        if($debugMode){
            $config = new Map(array(
                'rewrite' => '/{path}',
                'actual' => 'directControllerAccessRoute',
                'method' => null,
                'params' => new Map(array(
                    'path' => 'any',
                ))
            ));
            $router->add('packfire.directControllerAccess', new Route(
                    'packfire.directControllerAccess',
                    $config));
        }
        
        /* @var $route Route */
        $route = null;
        if($request->method() == HttpMethod::GET 
                && isset($this->ioc['config'])
                && $this->ioc['config']->get('routing', 'caching')){
            
            if(isset($this->ioc['cache'])){
                $cache = $this->ioc['cache'];
                $cacheId = 'route.' . $request->method() . sha1($request->uri() . $request->queryString());
                if($cache->check($cacheId)){
                    $route = $cache->get($cacheId);
                }else{
                    $route = $router->route($request);
                    if($route){
                        $cache->set($cacheId, $route, new TimeSpan(7200));
                    }
                }
            }
        }
        
        if(!$route){
            $route = $router->route($request);
        }
        $this->ioc['route'] = $route;
        $this->ioc['response'] = new Response();
        
        
        if($route instanceof RedirectRoute){
            $this->ioc['response'] = new RedirectResponse($route->redirect(), $route->code());
        }elseif($route){
            if(is_string($route->actual()) && strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }

            if($debugMode && $route->name() == 'packfire.directControllerAccess'){
                $caLoader = $this->directAccessProcessor();
            }else{
                $caLoader = new ControllerInvoker($class, $action);
            }
            $caLoader($this->ioc);
            if(!$caLoader->load()){
                throw new HttpException(404);
            }
        }else{
            throw new HttpException(404);
        }
    }
    
    /**
     * Handles unhandled exception in the application execution
     * @param Exception $exception The unhandled exception
     * @since 1.0-sofia
     */
    public function handleException($exception){
        $this->ioc['exception.handler']->handle($exception);
    }
    
    /**
     * Callback for Direct Controller Access Routing
     * @return ControllerInvoker Returns the loader
     * @since 1.0-sofia
     */
    public function directAccessProcessor(){
        $route = $this->ioc['route'];
        $path = $route->params()->get('path');
        $route->params()->removeAt('path');
        $class = '\\' . str_replace('/', '\\', dirname($path));
        $action = basename($path);
        $caLoader = new ControllerInvoker($class, $action);
        return $caLoader;
    }
    
}
