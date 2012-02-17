<?php
pload('packfire.IRunnable');
pload('packfire.net.http.pHttpResponse');

/**
 * The generic controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
abstract class pController {
    
    protected $restful = true;
    
    public function __construct(){
        
    }
    
    public function render($view){
        
    }
    
    public function model($model){
        pload('model.' . $model);
        $obj = new $model();
        return $obj;
    }
    
    /**
     * Run the controller with the route
     * @param pHttpClientRequest $request
     * @param pRoute $route
     * @param string $action
     * @since 1.0-sofia
     */
    public function run($request, $route, $action){
        if(!$action){
            $action = 'index';
        }
        
        $httpMethodCall = strtolower($route->httpMethod()) . ucFirst($action);
        if(is_callable(array($this, $httpMethodCall))){
            $action = $httpMethodCall;
        }
        
        $action = 'do' . ucFirst($action);
        
        $result = new pHttpResponse();
        if(is_callable(array($this, $action))){
            $this->$action();
        }
        
        return $result;
    }
    
}