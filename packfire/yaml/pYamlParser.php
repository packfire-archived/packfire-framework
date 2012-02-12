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
    
    /**
     *
     * @var string
     */
    private $postline = null;
    
    public function __construct($reader){
        $this->read = $reader;
    }
    
    public function parse(){
        // the overall Map
        $result = $this->parseNextBlock();
        //$result = $this->parseMapItems(0);
        return $result;
    }
    
    public function findDocumentStart(){
        $this->read->until('---');
//        $this->read->stream()->seek(function($x){
//            return $x->tell() + 3;
//        });
    }
    
    private function nextLine($line = null){
        if($line === null){
            if($this->postline !== null){
                $line = $this->postline;
                $this->postline = null;
            }else{
                $line = $this->read->line();
            }
        }
        $line = pYamlValue::stripComment($line);
        $trimmed = trim($line);
        $indentation = 0;
        if(strlen($trimmed) > 0){
            $indentation = strpos($line, $trimmed);
        }
        return array($line, $trimmed, $indentation);
    }
    
    public function parseKeyValue($line){
        $position = 0;
        $key = pYamlInline::load($line)->parseScalar($position, array(':', '#', "\n"));
        $after = $position + 2;
        if($after >= strlen($line)){
            $value = null;
        }else{
            $value = substr($line, $after);
        }
        return array($key, $value);
    }
    
//    public function parseNextUnit($minIndentation = 0){
//        $result = null;
//        list($line, $trimmed, $indentation) = $this->nextLine();
//        if(strlen($trimmed) > 0 && $trimmed[0] != '#'){
//            if($minIndentation <= $indentation){
//                list($key, $value) = $this->parseKeyValue($line);
//                if(strlen($trimmed) > 0 && substr(ltrim($line), 0, 2) == '- '){
//                    $result = array();
//                    if(strlen(substr($trimmed, 2)) == 0){
//                        // empty key, weird...
//                        // let's parse the whole block shall we?
//                        $block = trim($this->fetchBlock($minIndentation + 2, substr(ltrim($line), 2)));
//                        $result = $this->parseBlock($block);
//                    }else{
//                        list($key, $value) = $this->parseKeyValue(substr($trimmed, 2));
//                        
//                        if($key !== substr($trimmed, 2) && $value === null){
//                            // no value, check next line
//                            $block = trim($this->fetchBlock($minIndentation + 2, substr(ltrim($line), 2)));
//                            list($key, $value) = $this->parseKeyValue($block);
//                            var_dump('VALUE: ',$value);
//                            $value = $this->parseBlock($value);
//                            var_dump('VALUE2: ',$value);
//                            $result = array($key => $value);
//                        }elseif($key === substr($trimmed, 2) && $value == null){
//                            $result = $key;
//                        }else{
//                            $result = array($key => $value);
//                        }
//                        
//                    }
//                    var_dump($line,substr($trimmed, 2),$result, '--------------------------------');
//                }else{
//                    
//                }
////                if($value !== null){
////                    switch($value){
////                        case '|':
////
////                            break;
////                        case '>':
////
////                            break;
////                        default:
////                            if($value === '' && $key != $trimmed){
////                                // data either on next line onwards, or it's a nested sequence / array
////                                $firstLine = $this->read->line();
////                                $firstLine = pYamlValue::stripComment($firstLine);
////                                $this->parseValue($indentation, $firstLine);
////                            }elseif(!is_array($value)){
////                                $value = $this->parseValue($indentation, $value);
////                            }
////                            break;
////                    }
////                }
//            }
//        }
//        return $result;
//    }
    
    public function parseNextBlock(){
        $result = null;
        $trimmed = '';
        while($trimmed == '' && $this->read->stream()->tell() < $this->read->stream()->length()){
            list($line, $trimmed, $indentation) = $this->nextLine();
            if(strlen($trimmed) > 0){
                if(substr(ltrim($line), 0, 2) == pYamlPart::SEQUENCE_ITEM_BULLET){
                    $this->postline = $line;
                    $result = $this->parseSequenceItems($indentation);
                }
            }
        }
        return $result;
    }
    
    public function parseSequenceItems($minLevel){
        $result = array();
        $indentation = $minLevel;
        $line = null;
        while($minLevel <= $indentation){
            list($line, $trimmed, $indentation) = $this->nextLine();
            if($trimmed == ''){
                break;
            }
            if($minLevel <= $indentation
                    && substr(ltrim($line), 0, 2) == pYamlPart::SEQUENCE_ITEM_BULLET){
                list($key, $value) = $this->parseKeyValue(substr($trimmed, 2));
                if($key == substr($trimmed, 2) && $value === null){
                    // value
                    $result[] = $key;
                }elseif('' === $key && $value === null){
                    // - 
                    //   value
                    $value = $this->fetchBlock($indentation, substr($trimmed, 2));
                    $result[] = $value;
                }elseif($value === null){
                    // - key:
                    //     value
                    var_dump('inside');
                    $value = $this->parseNextBlock();
                    $result[$key] = $value;
                }else{
                    // - key: value
                    $lastLine = str_repeat(' ', $indentation + 2) .$key . ': '.$value;
                    $this->postline = $lastLine;
                    $result[] = $this->parseMapItems($indentation + 2);
                }
            }
        }
        $this->postline = $line;
        var_dump('SEQUENCE', $result);
        return $result;
    }
    
    public function parseMapItems($minLevel){
        $result = array();
        $indentation = $minLevel;
        $line = null;
        while($minLevel <= $indentation){
            list($line, $trimmed, $indentation) = $this->nextLine();
            if($trimmed == ''){
                break;
            }
            if($minLevel <= $indentation){
                list($key, $value) = $this->parseKeyValue($trimmed);
                if($value === null){
                    // value
                    $value = $this->fetchBlock($indentation, substr($trimmed, 2));
                    // TODO: something about the block
                    $result[$key] = $value;
                }else{
                    $result[$key] = pYamlInline::load($value)->parseValue();
                }
                
                // - value
                // - key: value
            }
        }
        $this->postline = $line;
        //var_dump('MAP', $result);
        return $result;
    }
    
    public function parseBlock($block){
        $result = $block;
        $firstChar = substr(trim($block), 0, 1);
        if($firstChar == '['){
            $result = pYamlInline::load($block)->parseSequence();
        }elseif($firstChar == '{'){
            $result = pYamlInline::load($block)->parseMap();
        }else{
            $blockLines = explode("\n", $block);
            list($key, $value) = $this->parseKeyValue($blockLines[0]);
            if($key != $blockLines[0]){
                // map!
                $result = pYamlInline::load('{' . $block . '}')->parseMap($position = 0, "\n");
            }else{
                // sequence
            }
        }
        return $result;
    }
    
    private function fetchBlock($minIndentation, $preline){
        $text = '';
        list($line, $trimmed, $indentation) = $this->nextLine($preline);
        $indentation = $minIndentation;
        while($minIndentation <= $indentation && $line){
            if($trimmed  == ''){
                break;
            }
            $text .= $trimmed . "\n";
            list($line, $trimmed, $indentation) = $this->nextLine();
        }
        if($minIndentation <= $indentation){
            $text .= $trimmed . "\n";
        }else{
            $this->postline = $line;
        }
        
        return $text;
    }
    
}