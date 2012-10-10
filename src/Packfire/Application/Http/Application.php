<?php
namespace Packfire\Application\Http;

use Packfire\Application\ServiceApplication;
use Response;
use ServiceBucket;
use Packfire\Exception\HttpException;
use Packfire\Exception\MissingDependencyException;
use Packfire\Controller\Invoker as ControllerInvoker;
use Packfire\Response\RedirectResponse;
use Packfire\Route\Http\RedirectRoute;
use Packfire\Net\Http\Method as HttpMethod;
use Packfire\DateTime\TimeSpan;

/**
 * Application class
 * 
 * The default web serving application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Http
 * @since 1.0-elenor
 */
class Application extends ServiceApplication {
    
    /**
     * Create a new Application object 
     * @since 1.0-elenor
     */
    public function __construct(){
        parent::__construct();
        $this->loadExceptionHandler();
        
        $httpLoader = new ServiceBucket($this->services);
        $httpLoader->load();
    }
    
    /**
     * Load the exception handler and prepare handlers
     * @since 1.0-elenor
     */
    protected function loadExceptionHandler(){
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
        $router = $this->service('router');
        /* @var $router Router */
        if(!$router){
            throw new MissingDependencyException('Router service missing.');
        }
        $router->load();
        
        /* @var $route Route */
        $route = null;
        if($request->method() == HttpMethod::GET 
                && $this->service('config.app')
                && $this->service('config.app')->get('routing', 'caching')){
            $cache = $this->service('cache');
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

            if($route->name() == 'packfire.directControllerAccess'){
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
        $this->service('exception.handler')->handle($exception);
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
        $class = $route->params()->get('class');
        $action = $route->params()->get('action');
        $route->params()->removeAt('class');
        $route->params()->removeAt('action');
        $caLoader = new ControllerInvoker(ucfirst($class), $action, $request, $route, $response);
        return $caLoader;
    }
    
}
