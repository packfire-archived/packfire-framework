<?php
namespace Packfire\Net\Http;

use Packfire\Collection\ArrayList;

/**
 * Utility class
 * 
 * utility for HTTP classes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Net\Http
 * @since 2.0.2
 */
class Utility {
    
    /**
     * Parse headers
     * @param string $strHeaders The string HTTP headers to parse
     * @param Map $headers The collection of headers to fill
     * @since 2.0.2
     */
    public static function parseHeaders($strHeaders, $headers){
        $matches = array();
        preg_match_all('`([^:\s]+): (.+(\n\s.+|))`', $strHeaders, $matches);
        $result = array_combine($matches[1], $matches[2]);
        foreach($result as $key => $value){
            $key = strtolower($key);
            $value = preg_replace('`\n\s+`', ' ', $value);
            if($headers->keyExists($key)){
                if($headers->get($key) instanceof ArrayList){
                    $headers->get($key)->add($value);
                }else{
                    $headers->add($key,
                            new ArrayList(array($headers->get($key), $value)));
                }
            }else{
                $headers->add($key, $value);
            }
        }
    }
    
}