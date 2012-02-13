<?php
Packfire::load('pYamlValue');
Packfire::load('pYamlInline');

/**
 * pYamlInline Description
 *
 * @author Sam Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since 1.0-sofia
 */
class pYamlInline {
    
    private $line;
    
    private $length;
    
    public function __construct($line){
        $this->line = $line;
        $this->length = strlen($line);
    }
    
    public static function load($line){
        $inline = new self($line);
        return $inline;
    }
    
    public function parseValue(&$position = 0, $breakers = array('{', ':','#', "\n")){
        $line = $this->line;
        $length = $this->length;
        $eov = false;
        while($position < $length && !$eov){
            switch($line[$position]){
                case '[':
                    $value = $this->parseSequence($position);
                    $eov = true;
                    break;
                case '{':
                    $value = $this->parseMap($position);
                    $eov = true;
                    break;
                case ' ':
                case ':':
                    // nothing to do here~
                    // fly off!
                    break;
                default:
                    $value = $this->parseScalar($position, $breakers);
                    $eov = true;
                    break;
            }
            ++$position;
        }
        return $value;
    }
    
    public function parseKeyValue(&$position = 0, $breakers = array('{', ':','#', "\n")){
        $result = array();
        $key = $this->parseScalar($position, $breakers, false);
        ++$position;
        $value = null;
        $value = $this->parseValue($position, $breakers);
        if($key){
            $result[$key] = $value;
        }else{
            $result[] = $value;
        }
        --$position;
        return $result;
    }
    
    public function parseScalar(&$position = 0, $breakers = array('#', "\n"), $translate = true){
        $result = '';
        $line = $this->line;
        $length = $this->length;
        if($length > 0){
            if($length > 1 && in_array($line[$position], pYamlPart::quotationMarkers())){
                $result = $this->parseQuotedString($position);
            }else{
                $result = $this->parseNormalScalar($position, $breakers);
            }
        }
        $result = trim($result);
        if($translate){
            $result = pYamlValue::translateScalar($result);
        }else{
            $result = pYamlValue::stripQuote($result);
        }
        return $result;
    }
    
    private function parseNormalScalar(&$position = 0, $breakers = array('#', "\n")){
        $offset = $position;
        $line = $this->line;
        $length = $this->length;
        while($position < $length){
            if(in_array($line[$position], $breakers)){
                --$position;
                break;
            }
            ++$position;
        }
        return substr($line, $offset, $position - $offset + 1);
    }
    
    private function parseQuotedString(&$position = 0){
        $line = $this->line;
        $length = $this->length;
        $offset = $position;
        $quote = $line[$position];
        while($position < $length){
            if($line[$position] == $quote){
                if($position - 1 > 1 && $line[$position - 1] != '\\'){
                    break;
                }elseif($position - 1 > 2 && $line[$position - 1] == '\\'
                        && $line[$position - 2] != '\\'){
                    // "Escaped!"
                    $line = substr($line, 0, $position - 1)
                            . substr($line, $position);
                    --$length;
                }
            }
            ++$position;
        }
        return substr($line, $offset, $position - $offset + 1);
    }
    
    public function parseSequence(&$position = 0, $separator = ','){
        $result = array();
        $line = $this->line;
        $length = $this->length;
        $eos = false;
        ++$position;
        while($position < $length && !$eos){
            switch($line[$position]){
                case '[': // nested sequence
                    $result[] = $this->parseSequence($position);
                    break;
                case '{': // nested map
                    $result[] = $this->parseMap($position);
                    break;
                case ']': // end of sequence
                    $eos = true;
                    break;
                case "\n":
                case ' ':
                case $separator:
                    // do nothing here
                    break;
                default:
                    $result[] = $this->parseScalar($position, array(',', ']', '#'));
                    break;
            }
            ++$position;
        }
        return $result;
    }
    
    public function parseMap(&$position = 0, $separator = ','){
        $result = array();
        $line = trim($this->line);
        $length = strlen($line);
        $eos = false;
        ++$position;
        // {computer: food, come: home, maybe: yes}
        while($position < $length && !$eos){
            switch($line[$position]){
                case '}': // end of sequence
                    $eos = true;
                    break;
                case "\n":
                case ' ':
                case $separator:
                    continue;
                    break;
                default:
                    $data = $this->parseKeyValue($position, array(':', $separator, '}'));
                    $result = array_merge($result, $data);
                    break;
            }
            ++$position;
        }
        --$position;
        return $result;
    }
    
}