<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\IO\File;

use Packfire\IO\File\InputStream as FileInputStream;
use Packfire\IO\IIOStream;

/**
 * A stream that reads and write to a file
 *
 * If the specified file does not exist, the class will not create the file
 * when opened. Instead the file will be created on the first write operation.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO\File
 * @since 1.0-sofia
 */
class Stream extends FileInputStream implements IIOStream {

    /**
     * Create a new Stream object
     * @param string $file The pathname to the file to stream
     * @since 1.0-sofia
     */
    public function __construct($file){
        parent::__construct($file);
    }

    /**
     * Does nothing, really.
     * @ignore
     * @since 1.0-sofia
     */
    public function flush() {
        // well, flush does nothing here at all (:
    }

    /**
     * Write data to the file.
     * @param string $data The data to write to the file.
     * @param integer $offset The position of the pointer to write the data to.
     * @param integer $length Amount of bytes to write (and replace) in file.
     * @since 1.0-sofia
     */
    public function write($data, $offset = null, $length = null) {
        switch(func_num_args()){
            case 3:
                if(null !== $offset){
                    $this->seek($offset);
                }
                fwrite($this->handle, $data, $length);
                break;
            case 2:
                $this->seek($offset);
            default:
                fwrite($this->handle, $data);
                break;
        }
    }

    /**
     * Open the stream
     * @since 1.0-sofia
     */
    public function open() {
        $this->handle = fopen($this->file, 'r+');
    }

}