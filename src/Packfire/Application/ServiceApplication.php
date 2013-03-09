<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application;

use Packfire\Application\IApplication;
use Packfire\FuelBlade\Container;

/**
 * A generic application that uses the service bucket
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application
 * @since 1.0-elenor
 */
abstract class ServiceApplication implements IApplication {
    
    protected $ioc;
    
    /**
     * Create a new ServiceApplication object
     * @since 1.0-elenor
     */
    public function __construct(){
        $this->ioc = new Container();
        $loader = new ServiceLoader();
        $loader($this->ioc);
    }
    
}