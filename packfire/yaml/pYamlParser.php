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
    
    /**
     *
     * @var integer
     */
    private $lineNumber = 0;
    
    public function __construct($reader){
        $this->read = $reader;
    }
    
    public function parse(){
        $result = array();
        // the overall Map
        while($this->read->stream()->tell() < $this->read->stream()->length()){
            $data = $this->parseNextBlock();
            $result += $data;
        }
        return $result;
    }
    
    public function findDocumentStart(){
        $this->read->until('---');
//        $this->read->stream()->seek(function($x){
//            return $x->tell() + 3;
//        });
    }
    
    private function prepareLine($line){
        $line = str_replace(array("\r\n", "\r"), array("\n", "\n"), $line);
        $line = pYamlValue::stripComment($line);
        return $line;
    }
    
    private function nextLine($line = null){
        if($line === null){
            if($this->postline !== null){
                $line = $this->postline;
                $this->postline = null;
            }else{
                $line = $this->read->line();
                ++$this->lineNumber;
            }
        }
        $line = $this->prepareLine($line);
        $trimmed = trim($line);
        $indentation = 0;
        if(strlen($trimmed) > 0){
            $indentation = strpos($line, $trimmed);
        }
        return array($line, $trimmed, $indentation);
    }
    
    public function parseKeyValue($line){
        $position = 0;
        $key = pYamlInline::load($line)->parseScalar($position, array(':', '{', '[', '#', "\n"), false);
        $after = $position + 2;
        if($after >= strlen($line)){
            $value = null;
        }else{
            $value = substr($line, $after);
        }
        return array($key, $value);
    }
    
    public function parseNextBlock($minLevel = 0){
        $result = array();
        $trimmed = '';
        while($trimmed == '' && $this->read->stream()->tell() < $this->read->stream()->length()){
            list($line, $trimmed, $indentation) = $this->nextLine();
            if($minLevel > $indentation){
                break;
            }
            if('...' == $trimmed){
                // document ended                
                break;
            }
            if(strlen($trimmed) > 0){
                if($trimmed[0] == '{'){
                    $this->postline = $line;
                    $result = trim($this->fetchBlock($indentation));
                    $result = pYamlInline::load($result)->parseMap();
                }elseif($trimmed[0] == '['){
                    $this->postline = $line;
                    $result = trim($this->fetchBlock($indentation));
                    $result = pYamlInline::load($result)->parseSequence();
                }elseif(substr(ltrim($line), 0, 2) == pYamlPart::SEQUENCE_ITEM_BULLET
                        || ltrim($line) == pYamlPart::SEQUENCE_ITEM_BULLET_EMPTYLINE){
                    var_dump('LINE', $line);
                    $this->postline = $line;
                    $result = $this->parseSequenceItems($indentation);
                    var_dump('RESULT', $result);
                }else{
                    if($this->hasKeyValueLine($trimmed)){
                        $this->postline = $line;
                        $result = $this->parseMapItems($indentation);
                    }
                }
            }
        }
        return $result;
    }
    
    private function hasKeyValueLine($line){
        list($key, ) = $this->parseKeyValue($line);
        return $key !== $line;
    }
    
    private function fetchFullValue($value, $minLevel){
        $result = $value;
        $trimmed = trim($value);
        if(strlen($trimmed) > 0){
            $lastchar = substr($trimmed, -1);
            switch($trimmed[0]){
                case '{':
                    if($lastchar != '}'){
                        $this->postline = str_repeat(' ', $minLevel) . $value;
                        $value = trim($this->fetchBlock($minLevel));
                    }
                    $result = pYamlInline::load($value)->parseMap();
                    break;
                case '[':
                    if($lastchar != ']'){
                        $this->postline = str_repeat(' ', $minLevel) . $value;
                        $value = trim($this->fetchBlock($minLevel));
                    }
                    $result = pYamlInline::load($value)->parseSequence();
                    break;
                case '|': // newlines preserved literal blocks
                    $result = trim($this->fetchBlock($minLevel));
                    break;
                case '>': // folded literal block
                    $value = trim($this->fetchBlock($minLevel));
                    $value = preg_replace(array('`\n\s+([^\s]+)`', '`([^\n]+)\n([^\n]+)`'), array("\n".'$1', '$1 $2'), $value);
                    $result = str_replace("\n\n", "\n", $value);
                    break;
                case '&': // reference creation
                    $result = $this->parseNextBlock($minLevel);
                    break;
                default: // normal scalar value
                    $result = pYamlInline::load($trimmed)->parseScalar();
                    break;
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
                    && (substr(ltrim($line), 0, 2) == pYamlPart::SEQUENCE_ITEM_BULLET
                    || ltrim($line) == pYamlPart::SEQUENCE_ITEM_BULLET_EMPTYLINE)){
                
                list($key, $value) = $this->parseKeyValue(substr($trimmed, 2));
                var_dump($key, $value);
                if($key == substr($trimmed, 2) && $key != '' && $value === null){
                    // value
                    $result[] = $key;
                }elseif('' === $key && $value === null){
                    // - 
                    //   value
                    $value = $this->parseNextBlock($minLevel + 2);
                }elseif('' === $key && null !== $value){
                    // - {key: value}
                   $this->postline = $line;
                   $result[] = $this->parseNextBlock($minLevel + 2);
                }else{
                    // - key: value
                    if($value === null){
                        $lastLine = str_repeat(' ', $indentation + 2) . $key . ': ';
                    }else{
                        $lastLine = str_repeat(' ', $indentation + 2) .$key . ': ' . $value;
                    }
                    $this->postline = $lastLine;
                    $result[] = $this->parseMapItems($indentation + 2);
                }
            }
        }
        $this->postline = $line;
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
                    // on its own...
                    $value = $this->parseNextBlock($minLevel + 2);
                }else{
                    // we've got a value and key!
                    $value = $this->fetchFullValue(trim($value), $minLevel + 2);
                }
                $result[$key] = $value;
            }
        }
        $this->postline = $line;
        return $result;
    }
    
    private function fetchBlock($minIndentation){
        $text = '';
        $indentation = $minIndentation;
        
        while($minIndentation <= $indentation
                && $this->read->stream()->tell() < $this->read->stream()->length()){
            
            list($line, $trimmed, $indentation) = $this->nextLine();
            
            if($trimmed == ''){
                $indentation = $minIndentation;
            }
            if($minIndentation <= $indentation){
                $text .= $line;
            }
        }
        if($minIndentation > $indentation){
            $this->postline = $line;
        }
        
        return $text;
    }
    
}