<?php
pload('packfire.application.pServiceApplication');
pload('pHttpAppResponse');
pload('pHttpServiceBucket');
pload('packfire.exception.handler.pHttpExceptionHandler');
pload('packfire.exception.handler.pErrorHandler');
pload('packfire.exception.pHttpException');
pload('packfire.exception.pMissingDependencyException');
pload('packfire.controller.pCALoader');
pload('packfire.response.pRedirectResponse');

/**
 * pApplication class
 * 
 * The default web serving application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.http
 * @since 1.0-elenor
 */
class pHttpApplication extends pServiceApplication {
    
    /**
     * Create the pHttpApplication object 
     * @since 1.0-elenor
     */
    public function __construct(){
        parent::__construct();
        $this->loadExceptionHandler();
        
        $httpLoader = new pHttpServiceBucket($this->services);
        $httpLoader->load();
    }
    
    /**
     * Load the exception handler and prepare handlers
     * @since 1.0-elenor
     */
    protected function loadExceptionHandler(){
        $this->services->put('exception.handler', new pHttpExceptionHandler());
        $handler = $this->service('exception.handler');
        $errorhandler = new pErrorHandler($handler);
        set_error_handler(array($errorhandler, 'handle'), E_ALL);
    }
    
    /**
     * Receive a request, process, and respond.
     * @param IAppRequest $request The request made
     * @return IAppResponse Returns the http response
     * @since 1.0-elenor
     */
    public function receive($request){
        $response = $this->prepareResponse($request);
        $router = $this->service('router');
        /* @var $router pRouter */
        if(!$router){
            throw new pMissingDependencyException('Router service missing.');
        }
        $router->load();
        /* @var $route pRoute */
        $route = null;
        if($this->service('config.app') && $this->service('config.app')->get('routing', 'caching') && $this->service('cache')){
            $cache = $this->service('cache');
            $id = 'route.' . $request->uri() . $request->method() . $request->queryString() . sha1(json_encode($request->post()->toArray()));
            if($cache->check($id)){
                $route = $cache->get($id);
            }else{
                $route = $router->route($request);
                if($this->service('cache')){
                    $cache->set($id, $route, new pTimeSpan(7200));
                }
            }
        }else{
            $route = $router->route($request);
        }
        
        if($route instanceof pRedirectRoute){
            $response = new pRedirectResponse($route->redirect(), $route->code());
        }elseif($route){
            if(is_string($route->actual()) && strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }

            if($route->name() == 'packfire.directControllerAccess'){
                $caLoader = $this->directAccessProcessor($request, $route, $response);
                $response = $caLoader->response();
            }else{
                $caLoader = new pCALoader($class, $action, $request, $route, $response);
                $caLoader->copyBucket($this);
                if($caLoader->load()){
                    $response = $caLoader->response();
                }else{
                    throw new pHttpException(404);
                }
            }
        }else{
            throw new pHttpException(404);
        }

        return $response;
    }
    
    /**
     * Create and prepare the response
     * @param pHttpRequest $request The request to respond to
     * @return pHttpResponse Returns the response prepared
     * @since 1.0-sofia
     */
    protected function prepareResponse($request){
        $response = new pHttpAppResponse();
        return $response;
    }
    
    /**
     * Handles unhandled exception in the application execution
     * @param Exception $exception The unhandled exception
     * @since 1.0-sofia
     */
    public function handleException($exception){
        $this->service('debugger')->exception($exception);
        $this->service('exception.handler')->handle($exception);
    }
    
    /**
     * Callback for Direct Controller Access Routing
     * @param IAppRequest $request The request
     * @param pRoute $route The route called
     * @param IAppResponse $response The response
     * @return pCALoader Returns the loader
     * @since 1.0-sofia
     */
    public function directAccessProcessor($request, $route, $response){
        $class = $route->params()->get('class');
        $action = $route->params()->get('action');
        $route->params()->removeAt('class');
        $route->params()->removeAt('action');
        $caLoader = new pCALoader(ucfirst($class), $action, $request, $route, $response);
        $caLoader->copyBucket($this);
        $ok = $caLoader->load(true);
        if(!$ok){
            throw new pHttpException(404);
        }
        return $caLoader;
    }
    
}
