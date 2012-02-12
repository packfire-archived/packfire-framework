<?php

/**
 * pYamlValue Description
 *
 * @author Sam Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since version-created
 */
class pYamlValue {
    
    public static function stripComment($line){
        $length = strlen($line);
        $position = 0;
        $escape = false;
        $string = false;
        $openQuote = null;
        $found = false;
        while($position < $length && !$found){
            switch($line[$position]){
                case '\\':
                    $escape = true;
                    break;
                case '#':
                    if(!$string){
                        --$position;
                        $found = true;
                    }
                    break;
                case '"':
                case '\'':
                    if(!$escape){
                        if($string){
                            if($openQuote == $line[$position]){
                                $openQuote = null;
                                $string = false;
                            }
                        }else{
                            $openQuote = $line[$position];
                            $string = true;
                        }
                    }
                default:
                    if($escape){
                        $escape = false;
                    }
                    break;
            }
            ++$position;
        }
        return substr($line, 0, $position);
    }
    
    public static function isQuoted($text){
        $len = strlen($text);
        return $len > 1 && in_array($text[0], pYamlPart::quotationMarkers()) && $text[0] == $text[$len - 1];
    }
    
    public static function stripQuote($text){
        return substr($text, 1, strlen($text) - 2);
    }
    
    public static function translateScalar($scalar){
        $result = $scalar;
        switch($scalar){
            case 'true':
            case 'TRUE':
                $result = true;
                break;
            case 'false':
            case 'FALSE':
                $result = false;
                break;
            case 'null':
            case 'NULL':
                $result = null;
                break;
        }
        if(is_numeric($result)){
            $result += 0;
        }
        if(self::isQuoted($result)){
            $result = self::stripQuote($result);
        }
        return $result;
    }
    
}