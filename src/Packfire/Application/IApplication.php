<?php
namespace Packfire\Application;

/**
 * IApplication interface
 * 
 * Generic application interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application
 * @since 1.0-sofia
 */
interface IApplication {
    
    /**
     * Receive a request, process, and respond.
     * @param ClientRequest $request The request made
     * @return IAppResponse Returns the response
     * @since 1.0-sofia
     */
    public function receive($request);
    
    /**
     * Handles unhandled exception in the application execution
     * @param Exception $exception The unhandled exception
     * @since 1.0-sofia
     */
    public function handleException($exception);
    
}