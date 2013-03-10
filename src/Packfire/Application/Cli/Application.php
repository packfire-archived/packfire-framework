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
    
    /**
     * Perform service loading processing
     * @param \Packfire\FuelBlade\Container $container
     * @since 2.1.0
     */
    public function __invoke($container){
        parent::__invoke($container);
        
        $loader = new ServiceLoader();
        $loader($this->ioc);
    }
    
    /**
     * Handles unhandled exception in the application execution
     * @param Exception $exception The unhandled exception
     * @since 1.0-elenor
     */
    public function handleException($exception) {
        $this->ioc['exception.handler']->handle($exception);
    }
    
}