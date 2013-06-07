<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\IO;

/**
 * Reading/writing operations to and from a stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO
 * @since 1.0-sofia
 */
interface IStream
{
    /**
     * Open the stream for access
     * @since 1.0-sofia
     */
    public function open();

    /**
     * Close the stream and release resources
     * @since 1.0-sofia
     */
    public function close();
}
