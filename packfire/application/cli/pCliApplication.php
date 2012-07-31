<?php
pload('packfire.application.pServiceApplication');
pload('pCliAppResponse');
pload('pCliServiceBucket');

/**
 * pCliApplication class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.cli
 * @since 1.0-elenor
 */
class pCliApplication extends pServiceApplication {
    
    public function __construct(){
        parent::__construct();
        
        $cliLoader = new pCliServiceBucket($this->services);
        $cliLoader->load();
    }
    
    /**
     * Load the exception handler and prepare handlers
     * @since 1.0-elenor
     */
    protected function loadExceptionHandler(){
        $this->services->put('exception.handler', new pCliExceptionHandler());
        $handler = $this->service('exception.handler');
        $errorhandler = new pErrorHandler($handler);
        set_error_handler(array($errorhandler, 'handle'), E_ALL);
    }
    
    /**
     * Create and prepare the response
     * @param IAppRequest $request The request to respond to
     * @return pHttpResponse Returns the response prepared
     * @since 1.0-sofia
     */
    protected function prepareResponse($request){
        $response = new pCliAppResponse();
        return $response;
    }
    
    /**
     * Handles unhandled exception in the application execution
     * @param Exception $exception The unhandled exception
     * @since 1.0-elenor
     */
    public function handleException($exception) {
        $this->service('exception.handler')->handle($exception);
    }

    public function receive($request) {
        $response = $this->prepareResponse($request);
        $router = $this->service('router');
        /* @var $router pRouter */
        if(!$router){
            throw new pMissingDependencyException('Router service missing.');
        }
        $router->load();
        /* @var $route pRoute */
        $route = null;
        if($this->service('config.app')->get('routing', 'caching') && $this->service('cache')){
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

        if($route){
            
        }else{
            throw new pInvalidRequestException('No default route specified.');
        }
        if(!$route){
            throw new pHttpException(404);
        }elseif($route instanceof pRedirectRoute){
            $response = new pRedirectResponse($route->redirect(), $route->code());
        }else{
            if(is_string($route->actual()) && strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }

            if($route->name() == 'packfire.directControllerAccess'){
                $response = $this->directAccessProcessor($request, $route, $response);
            }else{
                $caLoader = new pCALoader($class, $action, $request, $route, $response);
                $caLoader->copyBucket($this);
                $caLoader->load();
                $response = $caLoader;
            }
        }
        return $response;
    }
    
}