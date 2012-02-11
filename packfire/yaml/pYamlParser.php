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
            $this->parseNextUnit();
        }
    }
    
    public function findDocumentStart(){
        $this->read->until('---');
        $this->read->stream()->seek(function($x){
            return $x->tell() + 3;
        });
    }
    
    public function parseNextUnit($level = 0){
        $key = $this->parseKey();
        if($key){
            $line = trim($this->read->line());
            switch(substr($line, 0, 1)){
                case '':
                case '#':
                    // nothing on the line
                    break;
                case '[':
                    break;
                case '"':
                case '\'':
                    break;
            }
        }   
    }
    
    private function parseKey(){
        $key = $this->read->until(array(
            "\n",
            pYamlPart::KEY_VALUE_SEPARATOR
        ));
        $stopper = substr($key, -1);
        if($stopper != pYamlPart::KEY_VALUE_SEPARATOR){
            $key = null;
        }
        if($key){
            $trimKey = trim($key);
            $firstChar = substr($trimKey, 0, 1);
            if(($firstChar == '"' || $firstChar == '\'') && substr($trimKey, -1) != $firstChar){
                $key = substr($key, 1);
                $delimit = '\\';
                while($delimit != $firstChar){
                    $keyPart = $this->read->until(array(
                        '\\' . $firstChar,
                        $firstChar
                    ));
                    $delimit = substr($keyPart, -1);
                    $key .= $keyPart;
                }
            }
            $key = substr($key, 0, strlen($key) - 1);
        }
        return $key;
    }
    
    public function parseLineValue($line){
        
    }
    
    public function parseBlockLiteral(){
        
    }
    
    
    
}