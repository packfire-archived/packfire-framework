<?php
pload('IApplication');
pload('packfire.routing.pRoute');
pload('packfire.routing.pRouter');
pload('packfire.collection.pMap');
pload('packfire.net.http.pHttpResponse');
pload('packfire.ioc.pBucketUser');
pload('packfire.ioc.pServiceBucket');
pload('packfire.config.framework.pAppConfig');
pload('packfire.config.framework.pRouterConfig');
pload('packfire.session.pSession');
pload('packfire.session.storage.pSessionStorage');
pload('packfire.ioc.pServiceLoader');
pload('packfire.exception.handler.pExceptionHandler');
pload('packfire.exception.handler.pErrorHandler');
pload('packfire.exception.pHttpException');
pload('packfire.database.pDbConnectorFactory');
pload('packfire.datetime.pTimer');
pload('packfire.debugger.pDebugger');
pload('packfire.debugger.console.pConsoleDebugOutput');
pload('packfire.controller.pCALoader');

/**
 * pApplication class
 * 
 * The default web serving application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
class pApplication extends pBucketUser implements IApplication {
    
    /**
     * The exception handler
     * @var IExceptionHandler
     * @since 1.0-sofia
     */
    private $exceptionHandler;
    
    /**
     * Create the pApplication object 
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->services = new pServiceBucket();
        $this->loadExceptionHandler();
        $this->loadBucket();
    }
    
    /**
     * Load the bucket of services 
     * @since 1.0-sofia
     */
    protected function loadBucket(){
        $this->services->put('config.app', array('pAppConfig', 'load'));
        $this->services->put('config.routing', array('pRouterConfig', 'load'));
        $this->services->put('debugger', new pDebugger(new pConsoleDebugOutput()));
        $this->service('debugger')->enabled($this->service('config.app')->get('app', 'debug'));
        $this->services->put('router', $this->loadRouter());
        pServiceLoader::loadConfig($this->services);
        
        $databaseConfigs = $this->service('config.app')->get('database');
        foreach($databaseConfigs as $key => $databaseConfig){
            $dbPackage = ($key == 'default' ? '' : '.' . $key);
            $this->services->put('database' . $dbPackage 
                    . '.driver', pDbConnectorFactory::create($databaseConfig));
            $this->services->put('database' . $dbPackage,
                    $this->service('database' . $dbPackage . '.driver')->database());
        }
        
        $storageId = $this->service('config.app')->get('session', 'storageId');
        $storage = null;
        if($storageId){
            $storage = $this->service($storageId);
        }else{
            $storage = new pSessionStorage();
        }
        $this->services->put('session', new pSession($storage));
    }
    
    /**
     * Load the exception handler and prepare handlers
     * @since 1.0-sofia
     */
    protected function loadExceptionHandler(){
        $this->services->put('exception.handler', new pExceptionHandler());
        $handler = $this->service('exception.handler');
        $errorhandler = new pErrorHandler($handler);
        set_error_handler(array($errorhandler, 'handle'), E_ALL);
        set_exception_handler(array($this, 'handleException'));
    }
    
    /**
     * Receive a request, process, and respond.
     * @param IAppRequest $request The request made
     * @return IAppResponse Returns the http response
     * @since 1.0-sofia
     */
    public function receive($request){
        $router = $this->loadRouter();
        $response = $this->prepareResponse($request);
        $route = $router->route($request);
        if(is_null($route)){
            throw new pHttpException(404);
        }else{
            if(is_string($route->actual()) && strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }

            $caLoader = new pCALoader($class, $action, $request, $route, $response);
            $caLoader->copyBucket($this);
            $caLoader->load();
            $response = $caLoader;
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
        $response = new pHttpResponse();
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
     * Load the router and its configuration
     * @return pRouter Returns the router
     * @since 1.0-sofia
     */
    private function loadRouter(){
        $router = new pRouter();
        $settings = $this->service('config.routing');
        $routes = $settings->get();
        foreach($routes as $key => $data){
            $data = new pMap($data);
            $route = new pRoute($data->get('rewrite'), $data->get('actual'),
                    $data->get('method'), $data->get('params'));
            $router->add($key, $route);
        }
        $directControllerAccessRoute = new pRoute('/{class}/{action}',
                array($this, 'directAccessProcessor'),
                null,
                new pMap(array(
                    'class' => '([a-zA-Z0-9\_]+)',
                    'action' => '([a-zA-Z0-9\_]+)'
                )));
        $router->add('packfire.DCARoute', $directControllerAccessRoute);
        return $router;
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
        $caLoader->load(true);
        return $caLoader;
    }
    
}
