<?php
pload('packfire.io.IIOStream');

/**
 * A stream that reads and write to a file
 *
 * If the specified file does not exist, the class will not create the file
 * when opened. Instead the file will be created on the first write operation.
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io.file
 * @since 1.0-sofia
 */
class pFileStream implements IIOStream {
    
    /**
     * The file resource
     * @var resource
     * @since 1.0-sofia
     */
    private $handle;
    
    /**
     * The path name to the file
     * @var string 
     * @since 1.0-sofia
     */
    private $file;
    
    /**
     * Create a new pFileStream
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
    public function file(){
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
     * Close the stream 
     */
    public function close() {
        fclose($this->handle);
    }

    /**
     * Open the stream 
     */
    public function open() {
        $this->handle = fopen($this->file, 'r+');
    }
    
}