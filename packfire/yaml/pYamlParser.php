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
    
    public function parseNextUnit($minIndentation = -1){
        $result = null;
        $line = $this->read->line();
        $trimmed = trim($line);
        if(strlen($trimmed) > 1 && $trimmed[0] != '#'){
            $indentation = strpos($line, $trimmed);
            if($minIndentation < $indentation){
                $data = each(pYamlInline::parseKeyValue($line));
                list($key, $value) = $data;
                //var_dump($key, $value);
                if($value !== null){
                    switch($value){
                        case '|':

                            break;
                        case '>':

                            break;
                        default:
                            if($value === '' && $key != $trimmed){
                                // data either on next line onwards, or it's a nested sequence / array
                                $firstLine = $this->read->line();
                                $firstLine = pYamlValue::stripComment($firstLine);
                                $this->parseValue($indentation, $firstLine);
//                                $trimmedFirstLine = trim($firstLine);{
//                                if(strlen($trimmedFirstLine) > 1){
//                                    $keyValueCheck = each(pYamlInline::parseKeyValue($trimmedFirstLine));
//                                    if(substr($trimmedFirstLine, 0, 1) == '{'){
//                                        $this->parseValue($trimmedFirstLine);
//                                    }elseif(substr($trimmedFirstLine, 0, 1) == '[')
//                                        $this->parseValue($trimmedFirstLine);
//
//                                    }elseif($keyValueCheck['key'] != $trimmedFirstLine){
//                                        // a map
//                                        $this->parseMap($indentation, $keyValueCheck);
//                                    }elseif(substr($trimmedFirstLine, 0, 2) == '- '){
//                                        $this->parseSequence($indentation, $firstLine);
//                                    }else{
//                                        $this->fetchBlock($indentation, $firstLine);
//                                    }
//                                }
                            }elseif(!is_array($value)){
                                $value = $this->parseValue($indentation, $value);
                            }
                            break;
                    }
                }
                $result[$key] = $value;
            }
        }
        return $result;
    }
    
    private function fetchBlock($minIndentation, $firstLine){
        $text = '';
        $line = $firstLine;
        $trimmed = trim($line);
        if($trimmed == ''){
            return;
        }
        $indentationCount = strpos($line, $trimmed);
        $indentation = substr($line, 0, $indentationCount);
        while($minIndentation < $indentationCount){
            $text .= $trimmed . "\n";
            $line = $this->read->line();
            $trimmed = trim($line);
            if($trimmed == ''){
                break;
            }
            $indentationCount = strpos($line, $trimmed);
            $indentation = substr($line, 0, $indentationCount);
        }
    }
    
    private function parseValue($indentation, $value){
        $result = $value;
        $trimmed = trim($value);
        if(strlen($value) > 1){
            switch(substr($trimmed, 0, 1)){
                case '-':
                    if(substr($trimmed, 0, 2) == '- '){
                        $result = $this->parseSequence($indentation, $value);
                    }
                    break;
                case '{':
                    $line = $value;
                    while($line && substr(trim($line), -1) != '}'){
                        $line = $this->read->line();
                        $line = pYamlValue::stripComment($line);
                        $value .= $line;
                    }
                    $result = pYamlInline::parseMap($value);
                    break;
                case '[':
                    $line = $value;
                    while($line && substr(trim($line), -1) != ']'){
                        $line = $this->read->line();
                        $line = pYamlValue::stripComment($line);
                        $value .= $line;
                    }
                    $result = pYamlInline::parseSequence($value);
                    break;
            }
        }
        return $result;
    }
    
    private function parseSequence($minIndentation, $firstLine){
        $result = array();
        $buffer = array();
        $line = $firstLine;
        $trimmed = trim($line);
        if($trimmed == ''){
            return;
        }
        $indentationCount = strpos($line, $trimmed);
        $indentation = substr($line, 0, $indentationCount);
        while($minIndentation < $indentationCount){
            $heck = substr($trimmed, 0, 2);
            if($heck == '- '){
                $keyValueCheck = each(pYamlInline::parseKeyValue($heck));
                if($keyValueCheck['key'] == $trimmed){
                    $value = pYamlInline::parseScalar($heck);
                    $buffer[] = $value;
                    $result = array_merge($result, $buffer);
                    $buffer = array();
                }else
            }
            $line = $this->read->line();
            $trimmed = trim($line);
            if($trimmed == ''){
                break;
            }
            $indentationCount = strpos($line, $trimmed);
            $indentation = substr($line, 0, $indentationCount);
        }
        //var_dump($result);
        return $result;
    }
    
    private function parseMap($minIndentation, $check){
        $result = array($check['key'] => $check['value']);
        //var_dump($this->parseNextUnit($minIndentation));
    }
    
    private function parseBlockLiteral(){
        
    }
    
    public function parseFoldedBlockLiteral(){
        
    }
    
}