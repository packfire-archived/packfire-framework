<?php
pload('packfire.routing.pRoute');
pload('packfire.routing.pRouter');
pload('packfire.config.pRouterConfig');
pload('packfire.collection.pMap');
pload('packfire.net.http.pHttpResponse');

/**
 * Application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pApplication {
    
    /**
     * Receive a request, process, and respond.
     * @param pHttpClientRequest $request The request made
     * @return pHttpResponse Returns the http response
     */
    public function receive($request){
        $router = $this->loadRouter();
        $route = $router->route($request);
        if(strpos($route->actual(), ':')){
            list($class, $action) = explode(':', $route->actual());
        }else{
            $class = $route->actual();
            $action = '';
        }
        
        $response = $this->respond($request);
        
        if($class instanceof Closure){
            $response = $class($request, $route, $response);
        }else{
            if(is_string($class)){
                // call controller
                $class .= 'Controller';
                pload('controller.' . $class);
            }

            if(class_exists($class)){
                $controller = new $class($response);
                $response = $controller->run($request, $route, $action);
            }
        }
        return $response;
    }
    
    protected function respond($request){
        $response = new pHttpResponse();
        return $response;
    }
    
    private function loadRouter(){
        $router = new pRouter();
        $settings = pRouterConfig::load();
        $routes = $settings->get();
        foreach($routes as $key => $data){
            $data = new pMap($data);
            $route = new pRoute($data->get('rewrite'), $data->get('actual'), $data->get('method'), $data->get('params'));
            $router->add($key, $route);
        }
        return $router;
    }
    
}
