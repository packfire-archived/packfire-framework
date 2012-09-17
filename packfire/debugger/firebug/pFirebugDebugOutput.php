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
    
    /**
     * The buffer of messages
     * @var array
     * @since 1.1-sofia
     */
    private $buffer;
    
    /**
     * Create a new pFirebugDebugOutput object
     * @since 1.1-sofia
     */
    public function __construct(){
        $this->buffer = array();
    }

    /**
     * Output the console
     * @since 1.1-sofia 
     */
    public function output() {
        $html = '';
        $html .= 'console.group("Packfire Framework Debugger");' . "\n";
        foreach($this->buffer as $line){
            $html .= 'console.' . $line[0] . '(' . json_encode($line[1]) . ');' . "\n";
        }
        $html = 'console.groupEnd();' . "\n";
        return '<script type="text/javascript">(function(){' . $html . '})();</script>';
    }

    /**
     * Write the log message to the console
     * @param string $message The message to write to the console
     * @param string $value (optional) The secondary value to the message
     * @param string $type (optional) The type of message written, 
     *              defaults to 'log'.
     * @since 1.1-sofia
     */
    public function write($message, $value = null, $type = 'log') {
        $jsFunction = 'log';
        switch($type){
            case 'exception':
                $jsFunction = 'error';
                break;
            case 'query':
                $jsFunction = 'info';
                break;
        }
        $this->buffer[] = array($jsFunction,
            $message . ($value ? ' '. $value : ''));
    }
    
}