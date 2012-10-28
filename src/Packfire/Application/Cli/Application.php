<?php
namespace Packfire\Application\Cli;

use Packfire\Application\Cli\Response;
use Packfire\Application\Cli\ServiceBucket;
use Packfire\Application\ServiceApplication;

/**
 * Application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 1.0-elenor
 */
abstract class Application extends ServiceApplication {
    
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
    
}