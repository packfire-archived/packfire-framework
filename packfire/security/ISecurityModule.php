<?php

/**
 * ISecurityModule Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.security
 * @since 1.0-sofia
 */
interface ISecurityModule {
    
    /**
     * Retrieve the identity of the user
     * @return mixed Returns the identity of the user
     * @since 1.0-sofia
     */
    public function identity();
    
    /**
     * Authorize the user to access a route
     * @return boolean Returns true if the user is authorized to access the route,
     *                 false otherwise.
     * @since 1.0-sofia
     */
    public function authorize($route);
        
    /**
     * Authenticate the user
     * @return boolean Returns true if the user is authenticated, false otherwise.
     * @since 1.0-sofia
     */
    public function authenticate();
    
}