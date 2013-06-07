<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Debugger\Output;

use Packfire\Debugger\IOutput;

/**
 * Firebug output for Debugger
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Debugger\Output
 * @since 1.1-sofia
 */
class Firebug implements IOutput
{
    /**
     * The buffer of messages
     * @var array
     * @since 1.1-sofia
     */
    private $buffer;

    /**
     * Create a new Output object
     * @since 1.1-sofia
     */
    public function __construct()
    {
        $this->buffer = array();
    }

    /**
     * Output the console
     * @since 1.1-sofia
     */
    public function output()
    {
        if ($this->buffer) {
            $html = '';
            $html .= 'console.group("Packfire Framework Debugger");' . "\n";
            foreach ($this->buffer as $line) {
                $html .= 'console.' . $line[0] . '(' . json_encode($line[1]) . ');' . "\n";
            }
            $html .= 'console.groupEnd();' . "\n";
            echo '<script type="text/javascript">(function(){if (console) {' . $html . '}})();</script>';
        }
    }

    /**
     * Write the log message to the console
     * @param string $message The message to write to the console
     * @param string $value   (optional) The secondary value to the message
     * @param string $type    (optional) The type of message written,
     *              defaults to 'log'.
     * @since 1.1-sofia
     */
    public function write($message, $value = null, $type = 'log')
    {
        $jsFunction = 'log';
        switch ($type) {
            case 'exception':
                $jsFunction = 'error';
                break;
            case 'query':
                $jsFunction = 'info';
                break;
        }
        $this->buffer[] = array($jsFunction,
            $message . ($value ? ' ['. $value . ']' : ''));
    }
}
