<?php
Packfire::load('packfire.io.IIOStream');

/**
 * Provides stream operations to a string buffer
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.text
 * @since 1.0-sofia
 */
class pTextStream implements IIOStream {
    
    /**
     * The data stream
     * @var string
     */
    private $buffer;
    
    /**
     * The current pointer position in the buffer;
     * @var integer 
     */
    private $pointer;
    
    /**
     * Create a new pTextStream
     * @param string $data (optional) Intialize the stream with some data.
     */
    public function __construct($data = ''){
        $this->buffer = $data;
        $this->pointer = 0;
    }

    /**
     * Nothing to do here
     * @ignore me... #foreveralone
     */
    public function close() {
        // nothing here ^^
    }

    /**
     * Nothing to do here
     * @ignore me... #foreveralone
     */
    public function open() {
        // nothing here
    }
    
    /**
     * Get the length of the stream.
     * @return Returns the length of the stream.
     */
    public function length() {
        return strlen($this->buffer);
    }

    /**
     * Read data from the stream.
     * @param integer $length The amount of data to read.
     * @return string Returns the data read from the stream or NULL if the end
     *                of the stream has been reached.
     */
    public function read($length) {
        $data = null;
        if($this->pointer < $this->length()){
            $data = substr($this->buffer, $this->pointer, $length);
            $this->pointer += strlen($data);
        }
        return $data;
    }

    /**
     * Move the pointer to a specific position.
     * @param integer $position The position to move to.
     * @since 1.0-sofia
     */
    public function seek($position) {
        if($position >= 0 && $position < $this->length()){
            $this->pointer = $position;
        }else{
            // TODO: index out of range exception
        }
        
    }

    /**
     * Check if the stream is seekable.
     * @return boolean Returns true if the stream is seekable, false otherwise.
     * @since 1.0-sofia
     */
    public function seekable() {
        return true;
    }

    /**
     * Tell the current position of the pointer
     * @return integer Returns the current position.
     * @since 1.0-sofia
     */
    public function tell() {
        return $this->pointer;
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
     * Write data into the stream.
     * @param string $data The data to write into the stream.
     * @param integer $offset (optional) The position of the stream to write to.
     * @param integer $length (optional) The amount of data to write.
     * @since 1.0-sofia
     */
    public function write($data, $offset = null, $length = null) {
        switch(func_num_args()){
            case 3:
                $this->buffer = substr($this->buffer, 0, $offset)
                    . substr($data, 0, $length)
                    . substr($this->buffer, $offset + $length);
                $this->seek($offset + $length);                
                break;
            case 2:
                $this->buffer = substr($this->buffer, 0, $offset)
                    . $data . substr($this->buffer, $offset);
                $this->seek($offset + strlen($data));
                break;
            default:
                $this->buffer = substr($this->buffer, 0, $this->tell())
                    . $data . substr($this->buffer, $this->tell());
                $this->seek($this->tell() + strlen($data));
                break;
        }
    }
    
}