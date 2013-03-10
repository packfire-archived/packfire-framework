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
    }
    
    /**
     * Receive a request, process, and respond.
     * @param Packfire\Application\Http\Request $request The request made
     * @return Packfire\Application\IAppResponse Returns the http response
     * @since 1.0-elenor
     */
    public function receive($request){
        $oriMethod = $request->method();
        if($request->headers()->keyExists('X-HTTP-Method')){
            $oriMethod = $request->headers()->get('X-HTTP-Method');
        }
        if($request->headers()->keyExists('X-HTTP-Method-Override')){
            $oriMethod = $request->headers()->get('X-HTTP-Method-Override');
        }
        $request->method($oriMethod);
        
        $response = $this->prepareResponse($request);
        
        if(!isset($this->ioc['router'])){
            throw new MissingDependencyException('Router service required, but missing.');
            return;
        }
        /* @var $router Router */
        $router = $this->ioc['router'];
        $router->load();
        
        $debugMode = $this->ioc['config']
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
                && $this->ioc['config']
                && $this->ioc['config']->get('routing', 'caching')){
            $cache = $this->ioc['cache'];
            if($cache){
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
        
        if($route instanceof RedirectRoute){
            $response = new RedirectResponse($route->redirect(), $route->code());
        }elseif($route){
            if(is_string($route->actual()) && strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }

            if($debugMode && $route->name() == 'packfire.directControllerAccess'){
                $caLoader = $this->directAccessProcessor($request, $route, $response);
            }else{
                $caLoader = new ControllerInvoker($class, $action, $request, $route, $response);
            }
            $caLoader->copyBucket($this);
            if($caLoader->load()){
                $response = $caLoader->response();
            }else{
                throw new HttpException(404);
            }
        }else{
            throw new HttpException(404);
        }

        return $response;
    }
    
    /**
     * Create and prepare the response
     * @param Packfire\Application\IAppRequest $request The request to respond to
     * @return Packfire\Application\IAppResponse Returns the response prepared
     * @since 1.0-sofia
     */
    protected function prepareResponse($request){
        $response = new Response();
        return $response;
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
     * @param IAppRequest $request The request
     * @param Route $route The route called
     * @param IAppResponse $response The response
     * @return ControllerInvoker Returns the loader
     * @since 1.0-sofia
     */
    public function directAccessProcessor($request, $route, $response){
        $path = $route->params()->get('path');
        $route->params()->removeAt('path');
        $class = '\\' . str_replace('/', '\\', dirname($path));
        $action = basename($path);
        $caLoader = new ControllerInvoker($class, $action, $request, $route, $response);
        return $caLoader;
    }
    
}
