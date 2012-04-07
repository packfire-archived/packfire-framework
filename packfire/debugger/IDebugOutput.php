<?php

/**
 * Debugger's output abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger
 * @since 1.0-sofia
 */
interface IDebugOutput {
    
    public function write($message, $value = null, $type = 'log');
    
    public function output();
    
}