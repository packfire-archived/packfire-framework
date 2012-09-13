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
     * Flags whether to allow direct access to the controller actions
     * Defaults to false
     * @var boolean
     * @since 1.0-sofia
     */
    protected $directAccess = false;
    
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
        
        $this->filters = new pMap();
        $this->state = new pMap();
        $this->errors = new pMap();
        $this->models = new pMap();
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
     * Check for action method
     * @param string $method The method of the request in lower case
     * @param string $action The name of the controller action in lower camel casing.
     * @return string Returns the method name
     * @since 1.1-sofia
     */
    private function checkMethod($method, $action){
        $call = $action;
        $httpMethodCall = $method . ucfirst($action);
        if(is_callable(array($this, $httpMethodCall))){
            $call = $httpMethodCall;
        }
        return $call;
    }
    
    /**
     * Run the controller action with the route
     * @param pRoute $route The route that called for this controller
     * @param string $action The action to perform
     * @return mixed Returns the result of the action
     * @since 1.0-sofia
     */
    public function run($route, $action){
        $this->route = $route;
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
        
        $method = strtolower($this->request->method());
        $call = $this->checkMethod($method, '');
        if(!$call){
            $call = $this->checkMethod($method, $action);
        }
        if(!$call){
            $call = $this->checkMethod($method, 'index');
        }
        
        if($securityEnabled && !$this->service('security')->authorize($route)){
            $this->handleAuthorization();
            return;
        }
        
        if(is_callable(array($this, $call))){
            // call the controller action
            $this->activate($call);
            $result = call_user_func_array(array($this, $call), $route->params()->toArray());
            if($result){
                $this->response = $result;
            }
            $this->postProcess();
            $this->deactivate($call);
        }else{
            $errorMsg = sprintf('The requested action "%s" is not found' 
                                . ' in the controller "%s".',
                                $call, get_class($this));
            if($this->request instanceof pHttpRequest){
                throw new pHttpException(404, $errorMsg);
            }else{
                throw new pInvalidRequestException($errorMsg);
            }
        }
        return $this->response;
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