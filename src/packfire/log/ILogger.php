<?php
namespace Packfire\Log;

/**
 * ILogger interface
 * 
 * Logger abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Log
 * @since 1.0-sofia
 */
interface ILogger {
    
    /**
     * Write a log message
     * @param string $message The message to write
     * @since 1.0-sofia
     */
    public function log($message);
    
}