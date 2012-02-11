<?php

/**
 * Provides extended reading operations to an IInputStream
 *
 * @author Sam Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io
 * @since 1.0-sofia
 */
class pInputStreamReader {
    
    /**
     * The stream to read
     * @var IInputStream
     */
    private $stream;
    
    /**
     * Create a new pInputStreamReader
     * @param IInputStream $stream The input stream to read.
     */
    public function __construct($stream){
        $this->stream = $stream;
    }
    
    /**
     * Get the stream object
     * @return IInputStream Returns the input stream
     */
    public function stream(){
        return $this->stream;
    }
    
    /**
     * Read until a newline character.
     * @return string Returns the data read from the stream.
     */
    public function line(){
        return $this->until("\n");
    }
    
    /**
     * Read until a certain text is found. If the text is not found, data from 
     * the starting position until the end of the file will be returned.
     * @param string $search 
     * @return string Returns the data read from the stream.
     */
    public function until($search){
        $found = false;
        $buffer = '';
        while(!$found){
            $data = $this->stream->read(1024);
            if($data === null){
                $found = true;
            }else{
                $buffer .= $data;
                $pos = strpos($buffer, $search);
                if($pos !== false){
                    $this->stream->seek($this->stream->tell() - (strlen($buffer) - $pos) + strlen($search));
                    $buffer = substr($buffer, 0, $pos);
                    $found = true;
                }
            }
        }
        return $buffer;
    }
    
}