<?php

/**
 * PHP Error handling abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception.handler
 * @since 1.0-sofia
 */
interface IErrorHandler {
    
    public function handle($errno, $errstr, $errfile, $errline);
    
}