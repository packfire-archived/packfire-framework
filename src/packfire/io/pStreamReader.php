<?php

/**
 * pStreamReader class
 * 
 * Provides extended reading operations to an IInputStream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io
 * @since 1.0-sofia
 */
class pStreamReader {
    
    /**
     * The stream to read
     * @var IInputStream
     * @since 1.0-sofia
     */
    private $stream;
    
    /**
     * Create a new pStreamReader object
     * @param IInputStream $stream The input stream to read.
     * @since 1.0-sofia
     */
    public function __construct($stream){
        $this->stream = $stream;
        $this->stream->open();
    }
    
    /**
     * Get the stream object
     * @return IInputStream Returns the input stream
     * @since 1.0-sofia
     */
    public function stream(){
        return $this->stream;
    }
    
    /**
     * Read until a newline character.
     * @return string Returns the data read from the stream.
     * @since 1.0-sofia
     */
    public function line(){
        return $this->until("\n");
    }
    
    /**
     * Read until a certain text is found. If the text is not found, data from 
     * the starting position until the end of the file will be returned.
     * @param string|array|pList $search The text to read until. 
     * @return string Returns the data read from the stream.
     * @since 1.0-sofia
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
                        list($search, $pos) = $result;
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
    
    /**
     * strpos array implementation
     * @param string $string The string to look in
     * @param array $search The substrings to look for
     * @return array Returns the resulting array or null if not found.
     * @since 1.0-sofia
     * @internal
     */
    private static function strposa($string, $search){
        $result = null;
        foreach($search as $text){
            $tpos = strpos($string, $text);
            if($tpos !== false && (!$result || $tpos < $result[0])){
                $result = array(
                    $tpos,
                    $text
                );
            }
        }
        return $result;
    }
    
    /**
     * Check if the stream has more data
     * @return boolean Returns true if more data is available for reading, false
     *          otherwise.
     * @since 1.0-elenor
     */
    public function hasMore(){
        return $this->stream->tell() < $this->stream->length();
    }
    
}