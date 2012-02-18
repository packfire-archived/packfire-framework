<?php
pload('IAppResponse');
pload('packfire.collection.pMap');
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
abstract class pController implements IAppResponse {
    
    /**
     * The client request to this controller
     * @var pHttpClientRequest
     */
    protected $request;
    
    /**
     * The route that called for this controller
     * @var pRoute
     */
    protected $route;
    
    /**
     * The response this controller handles
     * @var IAppResponse
     */
    protected $response;
    
    /**
     * Flags whether this controller uses RESTful controller actions
     * @var boolean
     */
    protected $restful = true;
    
    /**
     * Controller Parameters
     * @var pMap
     */
    protected $params;
    
    /**
     * Create a new pController object
     * @param pHttpClientRequest $request The client's request
     * @param pHttpResponse $response The response object
     * @since 1.0-sofia
     */
    public function __construct($request, $response){
        $this->request = $request;
        $this->response = $response;
        $this->params = new pMap();
    }
    
    /**
     * Render the view for this controller
     * @param IView $view 
     */
    public function render($view){
        $output = $view->render();
        $this->response->body($output);
    }
    
    /**
     * Create and prepare a redirect to another URL
     * @param string $url The URL to route to
     * @param string $code (optional) The HTTP code. Defaults to 302 Found. 
     *                     Use constants from pHttpResponseCode
     */
    protected function redirect($url, $code = null){
        if(func_num_args() == 2){
            $this->response = new pRedirectResponse($url, $code);
        }else{
            $this->response = new pRedirectResponse($url);
        }
    }
    
    /**
     * Load and create a model from the application folder
     * @param string $model Name of the model to load
     * @return pModel Returns the model object
     * @since 1.0-sofia 
     */
    public function model($model){
        $model .= 'Model';
        pload('model.' . $model);
        $obj = new $model();
        return $obj;
    }
    
    /**
     * Run the controller with the route
     * @param pRoute $route The route that called for this controller
     * @param string $action The action to perform
     * @since 1.0-sofia
     */
    public function run($route, $action){
        $this->route = $route;
        $this->params = $route->params();
        
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
    }
    
    public function response() {
        return $this->response;
    }
    
}