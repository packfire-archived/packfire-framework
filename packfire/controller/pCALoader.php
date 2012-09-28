<?php
pload('packfire.ioc.pBucketUser');
pload('packfire.application.IAppResponse');
pload('packfire.pClassLoader');

/**
 * pCALoader class
 * 
 * Controller Access Loader
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.controller
 * @since 1.0-sofia
 */
class pCALoader extends pBucketUser {
    
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
     * @return boolean Returns true if loaded successfully, false otherwise.
     * @since 1.0-sofia
     */
    public function load(){
        $class = $this->package;
        if(is_string($this->package)){
            // call controller
            $isView = substr($class, -4) == 'View';

            list($package, $class) =
                    pClassLoader::resolvePackageClass($this->package);

            if($package == $class){
                // only class name is provided, so we use
                // the controllers in the controller folder
                
                if($isView){
                    pload('app.AppView');
                    try{
                        pload('view.' . $package);
                    }catch(pMissingDependencyException $ex){
                        // it is an attempt to load, so no need the exception
                    }
                }else{
                    if(substr($class, -11) != 'Controller'){
                        $package .= 'Controller';
                        $class .= 'Controller';
                    }
                    pload('app.AppController');
                    try{
                        pload('controller.' . $package);
                    }catch(pMissingDependencyException $ex){
                        // it is an attempt to load, so no need the exception
                    }
                }
                
            }else{
                // woah we've got a badass here
                // this is to load a custom class
                pload($package);
            }
            
            if(class_exists($class)){
                if($isView){
                    /* @var $view pView */
                    $view = new $class();
                    $view->copyBucket($this);
                    $output = $view->render();
                    $this->response()->body($output);
                }else{
                    /* @var $controller pController */
                    $controller = new $class($this->request, $this->response);
                    $controller->copyBucket($this);
                    $controller->run($this->route, $this->action);
                    $this->response = $controller->response();
                }
            }else{
                // oops! the class is really not found (:
                return false;
            }
        }else{
            // oops! no idea what you've given me as $class
            return false;
        }
        return true;
    }
    
    /**
     * Get the controller response
     * @return IAppResponse Returns the controller response
     * @since 1.0-sofia
     */
    public function response(){
        return $this->response;
    }
    
}