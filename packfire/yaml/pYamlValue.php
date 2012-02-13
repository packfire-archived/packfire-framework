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
        $start = null;
        $removals = array();
        while($position < $length){
            switch($line[$position]){
                case '\\':
                    $escape = true;
                    break;
                case "\n":
                    if(!$string && $start !== null){
                        $removals[$start] = $position;
                        $start = null;
                    }
                    break;
                case '#':
                    if(!$string){
                        $start = $position;
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
        if($start !== null){
            $removals[$start] = strlen($line);
        }
        $offset = 0;
        foreach($removals as $start => $end){
            $start -= $offset;
            $end -= $offset;
            $line = substr($line, 0, $start) . substr($line, $end);
            $offset += ($end - $start);
        }
        return $line;
    }
    
    public static function isQuoted($text){
        $len = strlen($text);
        return $len > 1 && in_array($text[0], pYamlPart::quotationMarkers()) && $text[0] == $text[$len - 1];
    }
    
    public static function stripQuote($text){
        $result = $text;
        if(self::isQuoted($text)){
            $result = substr($text, 1, strlen($text) - 2);
        }
        return $result;
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