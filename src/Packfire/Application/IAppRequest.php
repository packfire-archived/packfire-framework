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
 * Generic application request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application
 * @since 1.0-sofia
 */
interface IAppRequest {
    
    /**
     * Get the method of the application request
     * @return string Returns the method identifier
     * @since 1.1-sofia
     */
    public function method();
    
    /**
     * Get the parameters of the request
     * @return ArrayList|array Returns the parameters
     * @since 1.0-sofia 
     */
    public function params();
    
}