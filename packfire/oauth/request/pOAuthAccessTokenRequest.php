<?php
pload('packfire.oauth.http.pOAuthRequest');

/**
 * pOAuthAccessTokenRequest class
 * 
 * An OAuth request to request for the Access Token
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth.request
 * @since 1.1-sofia
 */
class pOAuthAccessTokenRequest extends pOAuthRequest { 
    
    /**
     * The request token granted by the service provider
     * @var string
     * @since 1.1-sofia
     */
    private $token;
    
    /**
     * Get or set the Access Token
     * @param string $key (optional) Set the Access Token
     * @return string Returns the Access Token
     * @since 1.1-sofia
     */
    public function token($token = null){
        if(func_num_args() == 1){
            $this->token = $token;
        }
        return $this->token;
    }
    
}