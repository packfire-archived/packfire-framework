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

/**
 * Application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
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
        $this->services->put('timer.app.start', new pTimer(true));
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
        $this->services->put('exception.handler', new pExceptionHandler());
        $this->services->put('debugger', new pDebugger(new pConsoleDebugOutput()));
        $this->service('debugger')->enabled($this->service('config.app')->get('app', 'debug'));
        $this->services->put('router', $this->loadRouter());
        pServiceLoader::loadConfig($this->services);
        
        $databaseConfigs = $this->service('config.app')->get('database');
        foreach($databaseConfigs as $key => $databaseConfig){
            $this->services->put('database.' . $key . '.driver',
                    pDbConnectorFactory::create($databaseConfig));
            $this->services->put('database' . ($key == 'default' ? '' : '.' . $key),
                    $this->service('database.' . $key . '.driver')->database());
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
        $handler = new pExceptionHandler();
        $errorhandler = new pErrorHandler($handler);
        set_error_handler(array($errorhandler, 'handle'), E_ALL);
        $this->exceptionHandler = $handler;
        set_exception_handler(array($this, 'handleException'));
    }
    
    /**
     * Receive a request, process, and respond.
     * @param pHttpClientRequest $request The request made
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
            if(strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }

            if($class instanceof Closure){
                $response = $class($request, $route, $response);
            }else{
                if(is_string($class)){
                    // call controller

                    list($package, $class) =
                            pClassLoader::resolvePackageClass($class);

                    if($package == $class){
                        // only class name is provided, so we use
                        // the controllers in the controller folder
                        if(substr($class, -11) != 'Controller'){
                            $package .= 'Controller';
                            $class .= 'Controller';
                        }
                        pload('app.AppController');
                        pload('controller.' . $package);
                    }else{
                        // woah we've got a badass here
                        // this is to load a custom class
                        pload($package);
                    }
                }

                if(class_exists($class)){
                    $controller = new $class($request, $response);
                    if($controller instanceof IBucketUser){
                        $controller->setBucket($this->services);
                    }
                    $controller->run($route, $action);
                    $response = $controller;
                }
            }
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
        $this->exceptionHandler->handle($exception);
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
        return $router;
    }
    
}
