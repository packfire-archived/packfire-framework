<?php
pload('IApplication');
pload('packfire.routing.pRoute');
pload('packfire.routing.pRouter');
pload('packfire.collection.pMap');
pload('packfire.net.http.pHttpResponse');
pload('packfire.ioc.pBucketUser');
pload('packfire.ioc.pServiceBucket');
pload('packfire.config.pAppConfig');
pload('packfire.config.pRouterConfig');

/**
 * Application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pApplication extends pBucketUser implements IApplication {
    
    public function __construct(){
        $this->bucket($this->createBucket());
    }
    
    protected function createBucket(){
        $bucket = new pServiceBucket();
        $bucket->put('config.app', array('pAppConfig', 'load'));
        $bucket->put('config.routing', array('pRouterConfig', 'load'));
        return $bucket;
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
            // todo: page not found
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
                    $class .= 'Controller';
                    pload('controller.' . $class);
                }

                if(class_exists($class)){
                    $controller = new $class($request, $response);
                    if($controller instanceof IBucketUser){
                        $controller->bucket($this->bucket);
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
     * Load the router and its configuration
     * @return pRouter Returns the router
     * @since 1.0-sofia
     */
    private function loadRouter(){
        $router = new pRouter();
        $settings = $this->bucket()->pick('config.routing');
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
