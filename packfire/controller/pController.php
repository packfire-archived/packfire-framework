<?php
pload('packfire.IAppResponse');
pload('packfire.collection.IList');
pload('IControllerFilter');
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
 * @package packfire.controller
 * @since 1.0-sofia
 */
abstract class pController extends pBucketUser implements IAppResponse {
    
    /**
     * The client request to this controller
     * @var pHttpClientRequest
     * @since 1.0-sofia
     */
    protected $request;
    
    /**
     * The route that called for this controller
     * @var pRoute
     * @since 1.0-sofia
     */
    protected $route;
    
    /**
     * The response this controller handles
     * @var IAppResponse
     * @since 1.0-sofia
     */
    protected $response;
    
    /**
     * The controller state
     * @var mixed
     * @since 1.0-sofia
     */
    protected $state;
    
    /**
     * Flags whether this controller uses RESTful controller actions
     * @var boolean
     * @since 1.0-sofia
     */
    protected $restful = true;
    
    /**
     * Controller Parameters
     * @var pMap
     * @since 1.0-sofia
     */
    protected $params;
    
    /**
     * Parameter filters
     * @var pMap 
     * @since 1.0-sofia
     */
    private $filters;
    
    /**
     * A collect of loaded models
     * @var pMap
     * @since 1.0-sofia
     */
    private $models;
    
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
        $this->filters = new pMap();
        $this->state = new pMap();
    }
    
    /**
     * Render the view for this controller
     * @param IView $view 
     * @since 1.0-sofia
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
     * @param boolean $forceReload (optional) If set to true, the method will 
     *                  create a new model instance. Defaults to false.
     * @return object Returns the model object
     * @since 1.0-sofia 
     */
    public function model($model, $forceReload = false){
        if($forceReload || !$this->models->keyExists($model)){
            $model .= 'Model';
            pload('model.' . $model);
            $this->models[$model] = new $model();
        }
        return $this->models[$model];
    }
    
    /**
     * Set filters to a parameter.
     * 
     * @param string $name Name of the parameter to add filters to
     * @param IControllerFilter|Closure|callback|array|IList $filter The controller filter,
     *              closure or callback that will process the parameter.
     *              If $filter is an array the method will run through the array
     *              recursively.
     * @param string $message (optional) The error message to use when a pValidationException occurs.
     * @since 1.0-sofia
     */
    protected function filter($name, $filter, $message = null){
        if(is_array($filter) || $filter instanceof IList){
            foreach($filter as $f){
                $this->filter($name, $f);
            }
        }else{
            $value = $this->params[$name];
            try{
                if($filter instanceof IControllerFilter){
                    $value = $filter->filter($value);
                }elseif($filter instanceof Closure || is_callable($filter)){
                    $value = $filter($value);
                }
            }catch(pValidationException $vEx){
                if(!$message){
                    $message = 'Invalid input: your input has failed the validation process.';
                }
                $this->service('form.feedback')->feedback($name, 'error', $message);
            }
            $this->params[$name] = $value;
        }
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
    
    /**
     * Get the response of this controller
     * @return IAppResponse
     * @since 1.0-sofia
     */
    public function response() {
        return $this->response;
    }
    
}