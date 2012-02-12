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
    
    public static function parseKeyValue($line, &$position = 0, $breakers = array('{', ':','#', "\n")){
        $result = array();
        $length = strlen($line);
        $key = self::parseScalar($line, $position, $breakers);
        ++$position;
        $eov = false;
        $value = null;
        while($position < $length && !$eov){
            switch($line[$position]){
                case '[':
                    $value = self::parseSequence($line, $position);
                    $eov = true;
                    break;
                case '{':
                    $value = self::parseMap($line, $position);
                    $eov = true;
                    break;
                case ' ':
                case ':':
                    // nothing to do here~
                    // fly off!
                    break;
                default:
                    $value = self::parseScalar($line, $position, $breakers);
                    $eov = true;
                    break;
            }
            ++$position;
        }
        if($key){
            $result[$key] = $value;
        }else{
            $result[] = $value;
        }
        --$position;
        return $result;
    }
    
    public static function parseScalar($line, &$position = 0, $breakers = array('#', "\n")){
        $result = '';
        $length = strlen($line);
        if($length > 0){
            if($length > 1 && in_array($line[$position], pYamlPart::quotationMarkers())){
                $result = self::parseQuotedString($line, $position);
            }else{
                $result = self::parseNormalScalar($line, $position, $breakers);
            }
        }
        return pYamlValue::translateScalar(trim($result));
    }
    
    private static function parseNormalScalar($line, &$position = 0, $breakers = array('#', "\n")){
        $offset = $position;
        $length = strlen($line);
        while($position < $length){
            if(in_array($line[$position], $breakers)){
                --$position;
                break;
            }
            ++$position;
        }
        return substr($line, $offset, $position - $offset + 1);
    }
    
    private static function parseQuotedString($line, &$position = 0){
        ++$position;
        $offset = $position;
        $length = strlen($line);
        $quote = $line[$position - 1];
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
        return substr($line, $offset, $position - $offset);
    }
    
    public static function parseSequence($line, &$position = 0){
        $result = array();
        $length = strlen($line);
        $eos = false;
        ++$position;
        while($position < $length && !$eos){
            switch($line[$position]){
                case '[': // nested sequence
                    $result[] = self::parseSequence($line, $position);
                    break;
                case '{': // nested map
                    $result[] = self::parseMap($line, $position);
                    break;
                case ']': // end of sequence
                    $eos = true;
                    break;
                case ' ':
                case ',':
                    // do nothing here
                    break;
                default:
                    $result[] = self::parseScalar($line, $position, array(',', ']', '#'));
                    break;
            }
            ++$position;
        }
        return $result;
    }
    
    public static function parseMap($line, &$position = 0){
        $result = array();
        $length = strlen($line);
        $eos = false;
        ++$position;
        // {computer: food, come: home, maybe: yes}
        while($position < $length && !$eos){
            switch($line[$position]){
                case '}': // end of sequence
                    $eos = true;
                    break;
                case ' ':
                case ',':
                    continue;
                    break;
                default:
                    $data = self::parseKeyValue($line, $position, array(':', ',', '}'));
                    $result = array_merge($result, $data);
                    break;
            }
            ++$position;
        }
        --$position;
        return $result;
    }
    
}