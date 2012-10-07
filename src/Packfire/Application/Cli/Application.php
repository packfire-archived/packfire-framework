<?php
namespace Packfire\Application\Cli;
use Response;
use ServiceBucket;
use Packfire\Application\ServiceApplication;
pload('packfire.exception.handler.pCliExceptionHandler');
pload('packfire.controller.pControllerInvoker');
pload('packfire.exception.pMissingDependencyException');

/**
 * Application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 1.0-elenor
 */
class Application extends ServiceApplication {
    
    public function __construct(){
        parent::__construct();
        
        $cliLoader = new ServiceBucket($this->services);
        $cliLoader->load();
    }
    
    /**
     * Create and prepare the response
     * @param IAppRequest $request The request to respond to
     * @return IAppResponse Returns the response prepared
     * @since 1.0-sofia
     */
    protected function prepareResponse($request){
        $response = new Response();
        return $response;
    }
    
    /**
     * Handles unhandled exception in the application execution
     * @param Exception $exception The unhandled exception
     * @since 1.0-elenor
     */
    public function handleException($exception) {
        $this->service('exception.handler')->handle($exception);
    }

    public function receive($request) {
        $response = $this->prepareResponse($request);
        $router = $this->service('router');
        /* @var $router pRouter */
        if(!$router){
            throw new pMissingDependencyException('Router service missing.');
        }
        $router->load();
        /* @var $route pRoute */
        $route = $router->route($request);

        if($route){
            if(is_string($route->actual()) && strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }
            
            $caLoader = new pControllerInvoker($class, $action, $request, $route, $response);
            $caLoader->copyBucket($this);
            if($caLoader->load()){
                $response = $caLoader->response();
            }else{
                throw new pInvalidRequestException('No route found.');
            }
        }else{
            throw new pInvalidRequestException('No default route specified.');
        }
        
        return $response;
    }
    
}