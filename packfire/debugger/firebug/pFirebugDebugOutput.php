<?php
pload('packfire.debugger.IDebugOutput');

/**
 * pFirebugDebugOutput class
 * 
 * Firebug output for Debugger
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger.firebug
 * @since 1.1-sofia
 */
class pFirebugDebugOutput implements IDebugOutput {
    
    private $buffer;
    
    public function __construct(){
        
    }

    public function output() {
        
    }

    public function write($message, $value = null, $type = 'log') {
        
    }
    
}