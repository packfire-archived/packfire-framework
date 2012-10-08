<?php

/**
 * IRoute interface
 * 
 * Route entry abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing
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