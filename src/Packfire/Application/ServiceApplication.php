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
    
    /**
     * The IoC container
     * @var \Packfire\FuelBlade\Container
     * @since 2.1.0
     */
    protected $ioc;
    
    /**
     * Perform service loading processing
     * @param \Packfire\FuelBlade\Container $container
     * @since 2.1.0
     */
    public function __invoke($container){
        $loader = new ServiceLoader();
        $loader($container);
        $this->ioc = $container;
        return $this;
    }
    
}