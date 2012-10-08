<?php
namespace Packfire\OAuth;

/**
 * Helper class
 * 
 * Provides helper methods for OAuth
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth
 * @since 1.1-sofia
 */
class pOAuthHelper {
    
    public static function urlencode($input){
        if(func_num_args() > 1){
            return array_map(__METHOD__, func_get_args());
        }else{
            if(is_array($input)){
                return array_map(__METHOD__, $input);
            }elseif(is_scalar($input)){
                return str_replace(
                    '+', ' ', str_replace('%7E', '~', rawurlencode($input))
                );
            }else{
                return '';
            }
        }
    }
    
    public static function urldecode($input){
        if(func_num_args() > 1){
            return array_map(__METHOD__, func_get_args());
        }else{
            if(is_array($input)){
                return array_map(__METHOD__, $input);
            }else{
                return urldecode($input);
            }
        }
    }
    
    /**
     * Generate a random nonce value
     * @param string $key (optional) Just something like a string you can pass it in.
     * @return string Returns the random nonce value
     */
    public static function generateNonce($key = __FILE__){
        $nt = microtime(true) . $key . mt_rand();
        return str_pad(base_convert(hash('sha256', $nt), 16, 36), 50, '0', STR_PAD_LEFT);
    }
    
    /**
     * Builds a query string based on RFC3986 
     * @param array|Map $data The data to feed the query string
     * @return string Returns the final query string
     * @since 1.1-sofia
     */
    public static function buildQuery($data){
        $pData = array();
        foreach($data as $key => $value){
            $pData[] = sprintf('%s=%s', self::urlencode($key), self::urlencode($value));
        }
        return implode('&', $pData);
    }
    
}