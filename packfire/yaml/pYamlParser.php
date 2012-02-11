<?php

/**
 * pYamlParser
 *
 * @author Sam Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.yaml
 * @since 1.0-sofia
 */
class pYamlParser {
    
    /**
     *
     * @var pInputStreamReader
     */
    private $read;
    
    public function __construct($reader){
        $this->read = $reader;
    }
    
    public function parse(){
        $this->findDocumentStart();
        while($this->read->stream()->tell() < $this->read->stream()->length() - 1){
            $line = $this->read->line();
            $trimmed = trim($line);
            if(substr($trimmed, 0, 1) != '#'){ // not a comment line
                $keySep = strpos($line, pYamlPart::KEY_VALUE_SEPARATOR);
                if($keySep !== false){
                    $key = trim(substr($line, 0, $keySep));
                }
            }
        }
    }
    
    public function parseUnit(){
        
    }
    
    public function findDocumentStart(){
        $this->read->until('---');
    }
    
    public function parseKey($line){
        return trim(substr($line, 0, strpos($line, ':')));
    }
    
    public function parseLineValue($line){
        
    }
    
    public function parseBlockLiteral(){
        
    }
    
    
    
}