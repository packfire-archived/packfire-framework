<?php
namespace Packfire\IO;

use IStream;

/**
 * IOutputStream interface
 * 
 * Output Stream for writing operations to a stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO
 * @since 1.0-sofia
 */
interface IOutputStream extends IStream {
    
    /**
     * Write to the output stream
     * @param string $data The data to write 
     * @param integer $offset (optional) The offset to start writing from
     * @param integer $length (optional) The amount of data to write and replace
     * @since 1.0-sofia
     */
    public function write($data, $offset = null, $length = null);
    
    /**
     * Performs flushing operation
     * @since 1.0-sofia 
     */
    public function flush();
    
}