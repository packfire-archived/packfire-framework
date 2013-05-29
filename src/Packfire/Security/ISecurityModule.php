<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Security;

/**
 * Provides interface for security module
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Security
 * @since 1.0-sofia
 */
interface ISecurityModule
{
    /**
     * Retrieve the identity of the user
     * @param  mixed $newIdentity (optional) The new identity in the session.
     * @return mixed Returns the identity of the user
     * @since 1.0-sofia
     */
    public function identity($newIdentity = null);

    /**
     * Authorize the user to access a route
     * @param  Route   $route The route to check authorization
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

    /**
     * Deauthenticate the user
     * @since 1.0-sofia
     */
    public function deauthenticate();

    /**
     * Get or set the request for HTTP checking
     * @param  mixed $context (optional) Set the request
     * @return mixed Returns the request
     * @since 1.1-sofia
     */
    public function request($request = null);

}
