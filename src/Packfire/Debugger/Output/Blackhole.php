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
 * Blackhole output. Nothing is written.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Debugger\Output
 * @since 2.1.0
 */
class Blackhole implements IOutput
{
    public function output()
    {
    }

    public function write($message, $value = null, $type = 'log')
    {
    }
}
