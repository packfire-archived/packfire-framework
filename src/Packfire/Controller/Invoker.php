<?php
namespace Packfire\Controller;

use Packfire\IoC\BucketUser;
use Packfire\Core\ActionInvoker;
use Packfire\IoC\IBucketUser;
use Packfire\Application\Pack\Template;

/**
 * Invoker class
 *
 * Controller Access Invoker
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Controller
 * @since 1.0-sofia
 */
class Invoker extends BucketUser {

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
     * @var ClientRequest
     * @since 1.0-sofia
     */
    private $request;

    /**
     * The Route that the application came through
     * @var Route
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
     * Create a new Invoker object
     * @param string $package The package to load the class
     * @param string $action The action to be loaded
     * @param IAppRequest $request The application request to load with
     * @param Route $route The route that was called
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
        if(is_string($class)){
            if(false !== strpos($class, '.')){ // check if there is an extension
                $template = Template::load($class);
                if($template){
                    $this->response->body($template->parse());
                }else{
                    return false;
                }
            }elseif(class_exists($class)){
                $isView = self::classInstanceOf($class, 'Packfire\View\IView');
                if($isView){
                    /* @var $view View */
                    $view = new $class();
                    $view->copyBucket($this);
                    $output = $view->render();
                    $this->response->body($output);
                }else{
                    if(self::classInstanceOf($class, 'Packfire\Controller\Controller')){
                        /* @var $controller Packfire\Controller\Controller */
                        $controller = new $class($this->request, $this->response);
                        $controller->copyBucket($this);
                        $this->response = $controller->actionRun($this->route, $this->action);
                    }else{
                        $controller = new $class();
                        if($controller instanceof IBucketUser){
                            $controller->copyBucket($this);
                        }
                        $actionInvoker = new ActionInvoker(array($controller, $this->action));
                        $this->response = $actionInvoker->invoke($this->route->params());
                    }
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

    protected static function classInstanceOf($className, $search){
        $classOnly = !interface_exists($search);
        $class = new \ReflectionClass($className);
        if(!$class) {
            return false;
        }
        do{
            $name = $class->getName();
            if($search == $name) {
                return true;
            }
            if(!$classOnly){
                $interfaces = $class->getInterfaceNames();
                if(is_array( $interfaces) && in_array($search, $interfaces)) {
                    return true;
                }
            }
            $class = $class->getParentClass();
        } while($class);
        return false;
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