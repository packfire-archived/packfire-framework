<?php
namespace Packfire\Application\Cli;

use Packfire\Application\Cli\Response;
use Packfire\Application\Cli\ServiceBucket;
use Packfire\Application\ServiceApplication;
use Packfire\Controller\Invoker as ControllerInvoker;
use Packfire\Exception\MissingDependencyException;
use Packfire\Exception\InvalidRequestException;

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

    /**
     * Receive a request, process, and respond.
     * @param ClientRequest $request The request made
     * @return IAppResponse Returns the response
     * @throws MissingDependencyException
     * @throws InvalidRequestException
     * @since 1.0-elenor
     */
    public function receive($request) {
        $response = $this->prepareResponse($request);
        $router = $this->service('router');
        /* @var $router Router */
        if(!$router){
            throw new MissingDependencyException('Router service missing.');
        }
        $router->load();
        /* @var $route Route */
        $route = $router->route($request);

        if($route){
            if(is_string($route->actual()) && strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }
            
            $caLoader = new ControllerInvoker($class, $action, $request, $route, $response);
            $caLoader->copyBucket($this);
            if($caLoader->load()){
                $response = $caLoader->response();
            }else{
                throw new InvalidRequestException('No route found.');
            }
        }else{
            throw new InvalidRequestException('No default route specified.');
        }
        
        return $response;
    }
    
}