<?php
Packfire::load('pYamlInline');

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
        $result = array();
        while($this->read->stream()->tell() < $this->read->stream()->length() - 1){
            $data = $this->parseNextUnit();
            if($data){
                $result = array_merge($result, $data);
            }
        }
        return $result;
    }
    
    public function findDocumentStart(){
        $this->read->until('---');
//        $this->read->stream()->seek(function($x){
//            return $x->tell() + 3;
//        });
    }
    
    public function parseNextUnit($level = 0){
        $result = null;
        $line = $this->read->line();
        $trimmed = trim($line);
        if(strlen($trimmed) > 1 && $trimmed[0] != '#'){
            $data = each(pYamlInline::parseKeyValue($line));
            $indentation = strpos($line, $trimmed);
            var_dump($data, $indentation);
            list($key, $value) = $data;
            switch($value){
                case '|':
                    
                    break;
                case '>':
                    
                    break;
                case '':
                    // data either on next line onwards, or it's a nested sequence / array
                    $nextLine = $this->read->line();
                    $trimmedNextLine = trim($nextLine);
                    if(strlen($trimmedNextLine) > 1){
                        
                    }else{
                        // empty line?? can't be...
                        $value = null;
                    }
                    break;
            }
            $result[$key] = $value;
        }
    }
    
    private function parseSequence(){
        
    }
    
    private function parseBlockLiteral(){
        
    }
    
    public function parseFoldedBlockLiteral(){
        
    }
    
}