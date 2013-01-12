<?php
namespace Packfire\Controller;

use Packfire\Collection\Map;
use Packfire\Response\RedirectResponse;
use Packfire\IoC\IBucketUser;
use Packfire\IoC\BucketUser;
use Packfire\Exception\HttpException;
use Packfire\Exception\AuthenticationException;
use Packfire\Exception\AuthorizationException;
use Packfire\Exception\InvalidRequestException;
use Packfire\Application\IAppResponse;
use Packfire\Net\Http\Request as HttpRequest;
use Packfire\Net\Http\Response as HttpResponse;
use Packfire\Core\ActionInvoker;
use Packfire\Route\Validator;

/**
 * Controller class
 * 
 * The generic controller class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Controller
 * @since 1.0-sofia
 */
abstract class Controller extends BucketUser {

    /**
     * The request to this controller
     * @var IAppRequest
     * @since 1.0-sofia
     */
    protected $request;

    /**
     * The route that called for this controller
     * @var Route
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
     * @var Map|mixed
     * @since 1.0-sofia
     */
    protected $state;

    /**
     * A collection of loaded models
     * @var Map
     * @since 1.0-sofia
     */
    private $models;

    /**
     * A collection of all the errors
     * @var Error
     * @since 1.0-sofia
     */
    protected $errors;
    
    /**
     * A validation callback handler
     * Gives the fields: $field, $value, $validity
     * @var Closure|callback
     * @since 2.0.0
     */
    protected $validationHandler;

    /**
     * Create a new Controller object
     * @param IAppRequest $request (optional) The client's request
     * @param IAppResponse $response (optional) The response object
     * @since 1.0-sofia
     */
    public function __construct($request = null, $response = null){
        $this->request = $request;
        $this->response = $response;

        $this->state = new Map();
        $this->errors = new Error();
        $this->models = new Map();
    }

    /**
     * Get the state of the controller
     * @return Map|mixed Returns the state of the controller
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
     *                     Use constants from Packfire\Net\Http\ResponseCode
     * @since 1.0-sofia
     */
    protected function redirect($url, $code = null){
        if(strlen($url) > 0 && $url[0] == '/'){
            $url = $this->service('config.app')->get('app', 'rootUrl') . $url;
        }
        if(func_num_args() == 2){
            $this->response = new RedirectResponse($url, $code);
        }else{
            $this->response = new RedirectResponse($url);
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
            $obj = new $model();
            if($obj instanceof BucketUser){
                $obj->copyBucket($this);
            }
            $this->models[$model] = $obj;
        }
        return $this->models[$model];
    }

    /**
     * Handler for checking authorization of this controller
     * @return boolean Returns true if the authorization succeeded,
     *               false otherwise.
     * @since 1.0-sofia
     */
    protected function handleAuthorization(){
        return true;
    }

    /**
     * Handler for checking authentication of this controller
     * @return boolean Returns true if the authorization succeeded,
     *               false otherwise.
     * @since 1.0-sofia
     */
    protected function handleAuthentication(){
        return true;
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
     * Method is called before controller action runs
     * @since 2.0.3
     */
    protected function beforeRun(){
        
    }
    
    /**
     * Method is called after controller action ran
     * @since 2.0.3
     */
    protected function afterRun(){
        
    }

    /**
     * Run the controller action with the route
     * @param \Packfire\Route\Route $route The route that called for this controller
     * @param string $action The action to perform
     * @return mixed Returns the result of the action
     * @since 1.0-sofia
     */
    public function actionRun($route, $action){
        $this->route = $route;
        
        if($this->validationHandler){
            $validator = new Validator($route->rules(),
                    $this->validationHandler);
            $params = array();
            $validator->validate($route->params(), $params);
        }
        
        $securityEnabled = $this->service('config.app')
                && !$this->service('config.app')->get('security', 'disabled');
        
        if($securityEnabled){
            $securityService = $this->service('security');
            if($securityService){
                // perform overriding of identity
                if($this->service('config.app')->get('secuity', 'override')){
                    $this->service('security')
                            ->identity($this->service('config.app')
                                    ->get('secuity', 'identity'));
                }
                $this->service('security')->request($this->request);
            }

            if(!$this->handleAuthentication()
                || ($securityService && !$securityService->authenticate())){
                throw new AuthenticationException('User is not authenticated.');
            }

            if(!$this->handleAuthorization()
                || ($securityService && !$securityService->authorize($route))){
                throw new AuthorizationException('Access not authorized.');
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
        if($call){
            $method = 'do' . ucfirst($call);
            if(is_callable(array($this, $method))){
                $call = $method;
            }
        }

        if(is_callable(array($this, $call))){
            
            $session = $this->service('session');
            if($session){
                /* @var $session \Packfire\Session\Session */
                $errors = $session->bucket('errors')->get('errors');
                $session->bucket('errors')->clear();
                if($errors){
                    $this->errors = new Error($errors);
                }
            }
            
            $this->beforeRun();
            // call the controller action
            $actionInvoker = new ActionInvoker(array($this, $call));
            $result = $actionInvoker->invoke($route->remap());
            if($result){
                if($route->response() && !($result instanceof IAppResponse)){
                    $response = $route->response();
                    $result = new $response($result);
                }
                $this->response = $result;
            }
            $this->processAftermath();
            $this->afterRun();
        }else{
            $errorMsg = sprintf('The requested action "%s" is not found'
                                . ' in the controller "%s".',
                                $call, get_class($this));
            if($this->request instanceof HttpRequest){
                throw new HttpException(404, $errorMsg);
            }else{
                throw new InvalidRequestException($errorMsg);
            }
        }
        return $this->response;
    }

    /**
     * Perform post-processing after the action is executed
     * @since 1.0-sofia
     */
    private function processAftermath(){           
        if($this->errors->exists()){
            $session = $this->service('session');
            /* @var $session \Packfire\Session\Session */     
            if($session){
                $session->bucket('errors')->set('errors', $this->errors->errors());
            }
        }
        
        // disable debugger if non-HTML output
        $type = null;
        if($this->response instanceof HttpResponse){
            $type = $this->response->headers()->get('Content-Type');
        }
        if($type && $this->service('debugger')
                && strpos(strtolower($type), 'html') === false){
            $this->service('debugger')->enabled(false);
        }
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