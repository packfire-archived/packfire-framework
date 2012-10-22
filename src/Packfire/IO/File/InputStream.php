<?php
namespace Packfire\IO\File;

use Packfire\IO\File\IFile;
use Packfire\IO\IInputStream;

/**
 * InputStream class
 *
 * An file input stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO\File
 * @since 1.0-sofia
 */
class InputStream implements IInputStream, IFile {

    /**
     * The file resource
     * @var resource
     * @since 1.0-sofia
     */
    protected $handle;

    /**
     * The path name to the file
     * @var string
     * @since 1.0-sofia
     */
    protected $file;

    /**
     * Create a new InputStream object
     * @param string $file The pathname to the file to stream
     * @since 1.0-sofia
     */
    public function __construct($file){
        $this->file = $file;
    }

    /**
     * Get the pathname to the file this stream is tunneling for.
     * @return string Returns the file pathname.
     * @since 1.0-sofia
     */
    public function pathname(){
        return $this->file;
    }

    /**
     * Get the file size of the file.
     * @return integer Returns the file size.
     * @since 1.0-sofia
     */
    public function length() {
        return filesize($this->file);
    }

    /**
     * Read data from the file stream.
     * @param integer $length The amount of bytes to read.
     * @return string Returns the data read from the file or NULL if end of file
     *                is reached.
     * @since 1.0-sofia
     */
    public function read($length) {
        if(feof($this->handle)){
            return null;
        }
        $result = fread($this->handle, $length);
        return $result;
    }

    /**
     * Set the file pointer position
     * @param integer $position The position of pointer to set to
     * @since 1.0-sofia
     */
    public function seek($position) {
        fseek($this->handle, $position, SEEK_SET);
    }

    /**
     * Query if the file can perform seek operation or not
     * @return boolean Returns true if seek operation can be performed, false
     *                 otherwise.
     * @since 1.0-sofia
     */
    public function seekable() {
        return 0 === fseek($this->handle, $this->tell());
    }

    /**
     * Get the position of the file pointer.
     * @return integer Returns the file pointer position.
     * @since 1.0-sofia
     */
    public function tell() {
        return ftell($this->handle);
    }

    /**
     * Close the stream
     * @since 1.0-sofia
     */
    public function close() {
        fclose($this->handle);
    }

    /**
     * Open the stream
     * @since 1.0-sofia
     */
    public function open() {
        $this->handle = fopen($this->file, 'r');
    }

}