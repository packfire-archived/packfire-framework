<?php
pload('packfire.application.IAppResponse');
pload('packfire.collection.IList');
pload('packfire.filter.IFilter');
pload('packfire.collection.pMap');
pload('packfire.response.pRedirectResponse');
pload('packfire.ioc.pBucketUser');
pload('packfire.exception.pHttpException');
pload('packfire.exception.pAuthenticationException');
pload('packfire.exception.pAuthorizationException');
pload('packfire..net.http.pHttpRequest');

/**
 * The generic controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.controller
 * @since 1.0-sofia
 */
abstract class pController extends pBucketUser {
    
    /**
     * The request to this controller
     * @var IAppRequest
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
     * @var pMap|mixed
     * @since 1.0-sofia
     */
    protected $state;
    
    /**
     * Flags whether this controller uses RESTful controller actions
     * Defaults to true.
     * @var boolean
     * @since 1.0-sofia
     */
    protected $restful = true;
    
    /**
     * Flags whether to allow direct access to the controller actions
     * Defaults to false
     * @var boolean
     * @since 1.0s-sofia
     */
    protected $directAccess = false;
    
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
     * A collection of loaded models
     * @var pMap
     * @since 1.0-sofia
     */
    private $models;
    
    /**
     * A collection of all the errors from the filtering 
     * @var pMap
     * @since 1.0-sofia 
     */
    private $errors;
    
    /**
     * Create a new pController object
     * @param IAppRequest $request (optional) The client's request
     * @param IAppResponse $response (optional) The response object
     * @since 1.0-sofia
     */
    public function __construct($request = null, $response = null){
        $this->request = $request;
        $this->response = $response;
        
        $this->params = new pMap();
        $this->filters = new pMap();
        $this->state = new pMap();
        $this->errors = new pMap();
        $this->models = new pMap();
        
        if($request){
            $this->params = $this->request->params();
        }
    }
    
    /**
     * Get the state of the controller
     * @return pMap|mixed Returns the state of the controller
     * @since 1.0-sofia
     */
    public function state(){
        return $this->state;
    }
    
    /**
     * Render the view for this controller
     * @param IView $view (optional) The view object to be rendered
     * @since 1.0-sofia
     */
    public function render($view = null){
        if($view){
            if($view instanceof IBucketUser){
                $view->copyBucket($this);
            }
            $view->state($this->state);
            $output = $view->render();
            $response = $this->response();
            if($response){
                $response->body($output);
            }
        }
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
     * @param string|pController $package Package of the controller to load
     * @param string $action (optional) The action to execute
     * @since 1.0-sofia
     */
    protected function forward($package, $action = null){
        
        if($package == $this && $action != null){
            $this->run($this->route, $action);
        }else{
            list($package, $class) = pClassLoader::resolvePackageClass($package);
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
            pload('model.' . $model);
            $obj = new $model();
            if($obj instanceof pBucketUser){
                $obj->copyBucket($this);
            }
            $this->models[$model] = $obj;
        }
        return $this->models[$model];
    }
    
    /**
     * Set filters to a parameter.
     * 
     * @param string $name Name of the parameter to add filters to
     * @param IFilter|Closure|callback|array|IList $filter The controller filter,
     *              closure or callback that will process the parameter.
     *              If $filter is an array the method will run through the array
     *              recursively.
     * @param string $message (optional) The error message to use when a pValidationException occurs.
     * @since 1.0-sofia
     */
    protected function filter($name, $filter, $message = null){
        if(is_string($filter)){
            $ex = explode('|', $filter);
            if(count($ex) > 1){
                $filter = $ex;
            }
        }
        if(is_array($filter) || $filter instanceof IList){
            foreach($filter as $f){
                $this->filter($name, $f, $message);
            }
        }else{
            $value = $this->params[$name];
            // if it is a name of class, create a new instance
            if(is_string($filter) && class_exists($filter)){
                $filter = new $filter();
            }
            try{
                if($filter instanceof IFilter){
                    $value = $filter->filter($value);
                }elseif($filter instanceof Closure || is_callable($filter)){
                    $value = $filter($value);
                }
            }catch(pValidationException $vEx){
                $this->error($name, $vEx, $message);
            }catch(Exception $ex){
                $this->error($name, $ex);
            }
            $this->params[$name] = $value;
        }
    }
    
    /**
     * Check if direct access is enabled on the controller
     * @return boolean Returns true if direct access is enabled,
     *           false otherwise.
     * @since 1.0s-sofia
     */
    public function directAccess(){
        return $this->directAccess;
    }
    
    /**
     * Get all the errors set to the controller
     * @return pMap Returns the list of errors
     * @since 1.0-sofia
     */
    public function errors(){
        return $this->errors;
    }
    
    /**
     * Set an error to the controller
     * @param string $target The name of the field that the error is targeted to
     * @param Exception $exception The exception that occurred
     * @param string $message (optional) The message to go with the error
     * @since 1.0-sofia
     */
    protected function error($target, $exception, $message = null){
        if(!$this->errors->keyExists($target)){
            $this->errors[$target] = new pList();
        }
        if(!$message){
            $message = $exception->getMessage();
        }
        $this->errors[$target]->add($message);
    }


    /**
     * Check if any error has been set to the controller
     * @return boolean Returns true if an error has been set, false otherwise.  
     * @since 1.0-sofia
     */
    public function hasError(){
        return $this->errors->count() > 0;
    }
    
    /**
     * Check if the controller is error free or not
     * @return boolean Returns true if there is no error set, false otherwise.
     * @since 1.0-sofia
     */
    public function isErrorFree(){
        return $this->errors->count() == 0;
    }
    
    /**
     * Handler for authorization error
     * @throws pAuthorizationException
     * @since 1.0-sofia 
     */
    protected function handleAuthorization(){
        throw new pAuthorizationException('Could not authorize user to access route.');
    }
    
    /**
     * Handler for authentication error
     * @throws pAuthenticationException
     * @since 1.0-sofia 
     */
    protected function handleAuthentication(){
        throw new pAuthenticationException('Could not authenticate user.');
    }
    
    /**
     * Run the controller with the route
     * @param pRoute $route The route that called for this controller
     * @param string $action The action to perform
     * @since 1.0-sofia
     */
    public function run($route, $action){
        $this->route = $route;
        $this->params->append($route->params());
        $securityEnabled = $this->service('security') 
                && !$this->service('config.app')->get('security', 'disabled');
        if($securityEnabled){
            if($this->service('config.app')->get('secuity', 'override')){
                $this->service('security')
                        ->identity($this->service('config.app')
                                ->get('secuity', 'identity'));
            }
            $this->service('security')->context($this);
            if(!$this->service('security')->authenticate()){
                $this->handleAuthentication();
                return;
            }
        }
        
        if(!$action){
            $action = 'index';
        }
        
        if(method_exists($this, $action)){
            $call = $action;
        }else{
            $call = 'do' . ucFirst($action);
        }
        
        if($this->restful){
            if($this->request instanceof pHttpRequest){
                $httpMethodCall = strtolower($this->request->method()) . ucFirst($action);
                if(method_exists($this, $httpMethodCall)){
                    $call = $httpMethodCall;
                }
            }elseif($this->request instanceof pCliAppRequest){
                $httpMethodCall = strtolower('cli') . ucFirst($action);
                if(method_exists($this, $httpMethodCall)){
                    $call = $httpMethodCall;
                }
            }
        }
        
        if($securityEnabled && !$this->service('security')->authorize($route)){
            $this->handleAuthorization();
            return;
        }
        
        if(method_exists($this, $call)){
            // call the controller action
            $this->activate($call);
            $result = $this->$call();
            if($result instanceof IAppResponse){
                $this->response = $result;
            }
            $this->postProcess();
            $this->deactivate($call);
        }else{
            if($this->request instanceof pHttpRequest){
                throw new pHttpException(404);
            }else{
                throw new pInvalidRequestException('The action is not found in the controller.');
            }
        }
    }
    
    /**
     * Get the controller's parameters.
     * @return pMap Returns pMap containing the parameters
     * @since 1.0-sofia
     */
    public function params(){
        return $this->params;
    }
    
    /**
     * Get or set a parameter of the controller's parameters
     * 
     * It's a candy for $this->params->get() instead
     * 
     * @param string $key The parameter key
     * @param mixed $value (optional) If set, the parameter will be set to
     *                      this value.
     * @return mixed Returns the parameter value or NULL if parameter is not set.
     * @since 1.1-sofia
     */
    public function param($key, $value = null){
        if(func_num_args() == 2){
            $this->params->add($key, $value);
        }
        return $this->params->get($key);
    }
    
    /**
     * Perform post-processing after the action is executed
     * @since 1.0-sofia 
     */
    private function postProcess(){
        
        // disable debugger if non-HTML output
        $type = null;
        if($this->response instanceof pHttpResponse){
            $type = $this->response->headers()->get('Content-Type');
        }
        if($this->service('debugger') 
                && $type 
                && strpos(strtolower($type), 'html') === false){
            $this->service('debugger')->enabled(false);
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