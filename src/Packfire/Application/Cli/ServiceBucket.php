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

use Packfire\IoC\BucketLoader;
use Packfire\Exception\Handler\CliHandler;
use Packfire\Debugger\Debugger;

/**
 * CLI application service bucket loader
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 1.0-elenor
 */
class ServiceBucket extends BucketLoader {
    
    /**
     * Perform loading of services for CLI
     * @since 1.0-elenor
     */
    public function load(){
        if(!$this->contains('exception.handler')){
            $this->put('exception.handler', new CliHandler());
        }
        if($this->pick('config.app')){
            // load the debugger
            $this->put('debugger', new Debugger());
            $this->pick('debugger')->enabled(false);
        }
    }
    
}