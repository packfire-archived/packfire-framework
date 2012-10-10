<?php
namespace Packfire\Application;

/**
 * IAppResponse interface
 * 
 * Abstraction for application response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
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