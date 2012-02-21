<?php

/**
 * Application interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
interface IApplication {
    
    /**
     * Receive a request, process, and respond.
     * @param pHttpClientRequest $request The request made
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