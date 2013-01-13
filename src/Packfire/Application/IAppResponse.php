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

/**
 * Abstraction for application response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c)  Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application
 * @since 1.0-sofia
 */
interface IAppResponse {
    
    /**
     * Get the output of the response
     * @return string The output of the response
     * @since 1.0-elenor
     */
    public function output();
    
}