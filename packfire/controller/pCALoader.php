<?php
pload('packfire.ioc.pBucketUser');
pload('packfire.application.IAppResponse');
pload('packfire.pClassLoader');

/**
 * Controller Access Loader
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.controller
 * @since 1.0-sofia
 */
class pCALoader extends pBucketUser implements IAppResponse {
    
    /**
     * The package name
     * @var string
     * @since 1.0-sofia
     */
    private $package;
    
    /**
     * The action to load
     * @var string
     * @since 1.0-sofia
     */
    private $action;
    
    /**
     * The request from the client
     * @var pHttpClientRequest
     * @since 1.0-sofia
     */
    private $request;
    
    /**
     * The Route that the application came through
     * @var pRoute
     * @since 1.0-sofia
     */
    private $route;
    
    /**
     * The response to the client
     * @var IAppResponse
     * @since 1.0-sofia
     */
    private $response;
    
    /**
     * Create a new pCALoader object
     * @param string $package The package to load the class
     * @param string $action The action to be loaded
     * @param IAppRequest $request The application request to load with
     * @param pRoute $route The route that was called
     * @param IAppResponse $response The response object
     * @since 1.0-sofia
     */
    public function __construct($package, $action, $request, $route, $response){
        $this->package = $package;
        $this->action = $action;
        $this->request = $request;
        $this->route = $route;
        $this->response = $response;
    }

    /**
     * Perform the loading process
     * @param boolean $directAccess (optional) Flags if the access is a direct
     *                access (DCA). Defaults to false.
     * @since 1.0-sofia
     */
    public function load($directAccess = false){
        $class = $this->package;
        if(is_string($this->package)){
            // call controller

            list($package, $class) =
                    pClassLoader::resolvePackageClass($this->package);

            if($package == $class){
                // only class name is provided, so we use
                // the controllers in the controller folder
                if(substr($class, -11) != 'Controller'){
                    $package .= 'Controller';
                    $class .= 'Controller';
                }
                pload('app.AppController');
                try{
                    pload('controller.' . $package);
                }catch(pMissingDependencyException $ex){
                    
                }
            }else{
                // woah we've got a badass here
                // this is to load a custom class
                pload($package);
            }
        }
        
        if(is_string($class) && class_exists($class)){
            /* @var $controller pController */
            $controller = new $class($this->request, $this->response);
            if($controller->directAccess() || 
                        (!$controller->directAccess() && !$directAccess)){
                $controller->copyBucket($this);
                $controller->run($this->route, $this->action);
                $this->response = $controller;
            }else{
                // throw 403 because the controller action exists, but
                // forbidden access because direct access was disabled
                throw new pHttpException(403);
            }
        }else if(is_callable($class)){
            $this->response = call_user_func($class, $this->request, $this->route, $this->response);
        }else{
            // oops! the class is really not found (:
            throw new pHttpException(404);
        }
    }
    
    /**
     * Get the controller response
     * @return IAppResponse Returns the controller response
     * @since 1.0-sofia
     */
    public function response(){
        return $this->response->response();
    }
    
}