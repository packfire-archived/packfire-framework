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

use Packfire\IO\IStream;

/**
 * Input Stream for reading operations from a stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO
 * @since 1.0-sofia
 */
interface IInputStream extends IStream
{
    /**
     * Read data from the stream
     * @param  integer $length The amount of data to read
     * @return string  Returns the data read from the stream
     * @since 1.0-sofia
     */
    public function read($length);

    /**
     * Move the pointer position to the specified position.
     * You can check whether the seek operation is supported by the stream using
     * the seekable() method.
     * @see IInputStream::seekable()
     * @param integer $position The position to move the pointer to
     * @since 1.0-sofia
     */
    public function seek($position);

    /**
     * Get the current pointer position
     * @return integer Returns the pointer position
     * @since 1.0-sofia
     */
    public function tell();

    /**
     * Check if the stream supports the seek operation.
     * @return boolean Returns true if the stream supports seek operation, false
     *              otherwise.
     * @since 1.0-sofia
     */
    public function seekable();

    /**
     * Get the amount of data available on the stream
     * @return integer Returns the amount of data available on the stream
     * @since 1.0-sofia
     */
    public function length();

}
