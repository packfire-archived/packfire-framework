<?php
pload('packfire.IRunnable');
pload('packfire.net.http.pHttpResponse');
pload('packfire.net.http.pRedirectResponse');

/**
 * The generic controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
abstract class pController {
    
    /**
     *
     * @var pHttpResponse
     */
    protected $response;
    
    /**
     *
     * @var boolean
     */
    protected $restful = true;
    
    /**
     * Controller Parameters
     * @var pMap
     */
    protected $params;
    
    public function __construct($response){
        $this->response = $response;
    }
    
    /**
     * 
     * @param IView $view 
     */
    public function render($view){
        $output = $view->render();
        $this->response->body($output);
    }
    
    protected function redirect($url, $code = null){
        $result = null;
        if(func_num_args() == 2){
            $result = new pRedirectResponse($url, $code);
        }else{
            $result = new pRedirectResponse($url);
        }
        return $result;
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
        
        if($this->restful){
            $httpMethodCall = strtolower($route->httpMethod()) . ucFirst($action);
            if(is_callable(array($this, $httpMethodCall))){
                $action = $httpMethodCall;
            }
        }
        
        $action = 'do' . ucFirst($action);
        
        if(is_callable(array($this, $action))){
            $this->$action();
        }
        
        return $this->response;
    }
    
}