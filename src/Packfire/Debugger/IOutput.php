<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Debugger;

/**
 * Debugger's output abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Debugger
 * @since 1.0-sofia
 */
interface IOutput
{
    /**
     * Write the log message to the debugging output channel
     * @param string $message The message to write
     * @param string $value   (optional) The secondary value to the message
     * @param string $type    (optional) The type of message written,
     *              defaults to 'log'.
     * @since 1.0-sofia
     */
    public function write($message, $value = null, $type = 'log');

    /**
     * Output
     * @since 1.0-sofia
     */
    public function output();

}
