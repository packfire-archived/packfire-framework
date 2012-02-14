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
        $this->stream->open();
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
     * @param string|array|pList $search The text to read until. 
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
                $pos = false;
                if(is_array($search)){
                    $result = self::strposa($buffer, $search);
                    if($result){
                        $search = $result['text'];
                        $pos = $result['position'];
                    }
                }else{
                    $pos = strpos($buffer, $search);
                }
                if($pos !== false){
                    $searchLen = strlen($search);
                    $this->stream->seek($this->stream->tell() - (strlen($buffer) - $pos) + $searchLen);
                    $buffer = substr($buffer, 0, $pos + $searchLen);
                    $found = true;
                }
            }
        }
        return $buffer;
    }
    
    private static function strposa($string, $search){
        $result = false;
        foreach($search as $text){
            $tpos = strpos($string, $text);
            if($tpos !== false && (!$result || $tpos < $result['position'])){
                $result = array(
                    'position' => $tpos,
                    'text' => $text
                );
            }
        }
        return $result;
    }
    
}