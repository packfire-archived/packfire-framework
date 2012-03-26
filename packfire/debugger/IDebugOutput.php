<?php

/**
 * IDebugOutput Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger
 * @since 1.0-sofia
 */
interface IDebugOutput {
    
    public function write($message, $value = null, $type = 'log');
    
    public function output();
    
}