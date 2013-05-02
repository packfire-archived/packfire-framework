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

use Packfire\Exception\Handler\CliHandler;
use Packfire\FuelBlade\IConsumer;

/**
 * CLI Application Service Loader
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Cli
 * @since 2.1.0
 */
class ServiceLoader implements IConsumer {
    
    public function __invoke($c) {
        if(!isset($c['exception.handler'])){
            $c['exception.handler'] = $c->share(function(){
                return new CliHandler();
            });
        }
        return $this;
    }
    
}