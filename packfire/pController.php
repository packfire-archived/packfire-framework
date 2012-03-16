<?php
pload('IAppResponse');
pload('packfire.collection.pMap');
pload('packfire.net.http.pHttpResponse');
pload('packfire.net.http.pRedirectResponse');
pload('packfire.ioc.pBucketUser');
pload('packfire.exception.pHttpException');

/**
 * The generic controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
abstract class pController extends pBucketUser implements IAppResponse {
    
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
     * The controller state
     * @var mixed
     */
    protected $state;
    
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
        $this->state = new pMap();
    }
    
    /**
     * Render the view for this controller
     * @param IView $view 
     */
    public function render($view){
        if($view instanceof IBucketUser){
            $view->setBucket($this->services);
        }
        $output = $view->render();
        $this->response->body($output);
    }
    
    /**
     * Create and prepare a redirect to another URL
     * @param string $url The URL to route to
     * @param string $code (optional) The HTTP code. Defaults to "302 Found". 
     *                     Use constants from pHttpResponseCode
     * @since 1.0-sofia
     */
    protected function redirect($url, $code = null){
        if(strlen($url) > 0 && $url[0] == '/'){
            $url = $this->service('config.app')->get('app', 'rootUrl') . $url;
        }
        if(func_num_args() == 2){
            $this->response = new pRedirectResponse($url, $code);
        }else{
            $this->response = new pRedirectResponse($url);
        }
    }
    
    /**
     * Forward the request to another controller
     * @param string $controller Package of the controller to load
     * @param string $action (optional) The action to execute
     * @since 1.0-sofia
     */
    protected function forward($package, $action = null){
        list($package, $class) = pClassLoader::resolvePackageClass($controller);

        if(substr($class, -11) != 'Controller'){
            $package .= 'Controller';
            $class .= 'Controller';
        }
        
        if(!class_exists($class)){
            pload('controller.' . $package);
        }
        
        if(is_subclass_of($class, 'pController')){
            $controller = new $class($this->request, $this->response);
            $controller->state = $this->state;
            $controller->setBucket($this->services);
            $controller->run($this->route, $action);
            $this->state = $controller->state;
            $this->response = $controller->response();
        }
    }
    
    /**
     * Get a specific routing URL from the router service
     * @param string $key The routing key
     * @param array $params (optionl) URL Parameters 
     * @return string Returns the URL
     * @since 1.0-sofia
     */
    protected function route($key, $params = array()){
        $router = $this->service('router');
        $url = $router->to($key, $params);
        if(strlen($url) > 0 && $url[0] == '/'){
            $url = $this->service('config.app')->get('app', 'rootUrl') . $url;
        }
        return $url;
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
        
        $action = 'do' . ucFirst($action);
        
        if($this->restful){
            $httpMethodCall = strtolower($route->httpMethod()) . ucFirst($action);
            if(is_callable(array($this, $httpMethodCall))){
                $action = $httpMethodCall;
            }
        }
        
        if(is_callable(array($this, $action))){
            // call the controller action
            $this->activate($action);
            $this->$action();
            $this->deactivate($action);
        }else{
            throw new pHttpException(404);
        }
    }
    
    /**
     * Called before the action is executed.
     * Feel free to override this method.
     * @param string $action The name of the action called.
     * @since 1.0-sofia 
     */
    public function activate($action){
        
    }
    
    /**
     * Called after the action is executed.
     * Feel free to override this method.
     * @param string $action The name of the action executed.
     * @since 1.0-sofia 
     */
    public function deactivate($action){
        
    }
    
    public function response() {
        return $this->response;
    }
    
}