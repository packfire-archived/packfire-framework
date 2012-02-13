<?php

/**
 * Provides API for working on YAML values.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.yaml
 * @since 1.0-sofia
 */
class pYamlValue {
    
    /**
     * Strip comments off the text
     * 
     * If the text has multiple lines, the method will remove comments from all
     * the lines.
     * 
     * @param string $line The text to remove comments from.
     * @return string Returns the text without any comments
     * @since 1.0-sofia
     */
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
    
    /**
     * Check whether if text is quoted or not.
     * @param string $text The text to check
     * @return boolean Returns true if the text is quoted, false otherwise. 
     * @since 1.0-sofia
     */
    public static function isQuoted($text){
        $text = trim($text);
        $len = strlen($text);
        return $len > 1 && in_array($text[0], pYamlPart::quotationMarkers()) && $text[0] == $text[$len - 1];
    }
    
    /**
     * Strip quotation marks if the text is wrapped by them.
     * @param string $text The text to strip quotes
     * @return string Returns the processed string
     * @since 1.0-sofia
     */
    public static function stripQuote($text){
        $result = $text;
        if(self::isQuoted($text)){
            $result = substr($text, 1, strlen($text) - 2);
        }
        return $result;
    }
    
    /**
     * Process a scalar value
     * @param string $scalar The value to process
     * @return string Returns the processed scalar value.
     * @since 1.0-sofia
     */
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
        $quoted = self::isQuoted($result);
        if(!$quoted || ($quoted && $result[0] != '\'')){
            $result = self::unescape($result);
        }
        if($quoted){
            $result = self::stripQuote($result);
        }
        return $result;
    }
    
    /**
     * Process and unescape characters from a text
     * @param string $text The text to process
     * @return string Returns the processed text
     * @since 1.0-sofia
     */
    public function unescape($text){
        $replace = array(
            '\n' => "\n",
            '\r' => "\r",
            '\t' => "\t",
            '\0' => "\0",
        );
        $text = str_replace(array_keys($replace), $replace, $text);
        return $text;
    }
    
}