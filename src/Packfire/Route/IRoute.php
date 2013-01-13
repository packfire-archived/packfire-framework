<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Route;

/**
 * Route entry abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route
 * @since 1.0-elenor
 */
interface IRoute {
    
    /**
     * Create a new IRoute object
     * @param string $name The name of the route
     * @param array|Map $data The configuration data entry
     * @since 1.0-elenor
     */
    public function __construct($name, $data);
    
    /**
     * Check whether the route matches the request
     * @param IAppRequest $locator The locator requested by the client
     * @return boolean Returns true if the route matches, false otherwise
     * @since 1.0-elenor 
     */
    public function match($request);
    
    /**
     * Get the name of the route
     * @return string Returns name of the route
     * @since 1.0-elenor
     */
    public function name();
    
}