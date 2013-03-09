<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application\Cli;

use Packfire\Application\Cli\Response;
use Packfire\Application\Cli\ServiceLoader;
use Packfire\Application\ServiceApplication;

/**
 * A CLI Application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 1.0-elenor
 */
abstract class Application extends ServiceApplication {
    
    public function __construct(){
        parent::__construct();
        
        $loader = new ServiceLoader();
        $loader($this->ioc);
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