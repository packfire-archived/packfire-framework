<?php
pload('packfire.ioc.pBucketUser');
pload('packfire.IAppResponse');
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
     * @param string $package
     * @param string $action
     * @param pHttpClientRequest $request
     * @param pRoute $route
     * @param IAppResponse $response 
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
     * Perform the loading
     * @param boolean $directAccess (optional) Flags if the access is a direct
     *                          access (DCA). Defaults to false.
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
                pload('controller.' . $package);
            }else{
                // woah we've got a badass here
                // this is to load a custom class
                pload($package);
            }
        }
        $controller = new $class($this->request, $this->response);
        if($controller->directAccess() || 
                (!$controller->directAccess() && !$directAccess)){
            if($controller instanceof IBucketUser){
                $controller->copyBucket($this);
            }
            $controller->run($this->route, $this->action);
            $this->response = $controller;
        }else{
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