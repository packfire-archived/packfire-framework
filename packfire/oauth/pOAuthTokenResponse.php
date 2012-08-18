<?php
pload('packfire.application.http.pHttpAppResponse');

/**
 * pOAuthTokenResponse class
 * 
 * OAuth Response for any token requests
 *
 * @package packfire.oaut
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD Licenseh.response
 * @since 1.1-sofia
 */
class pOAuthTokenResponse extends pHttpAppResponse {
    
    /**
     * The response token
     * @var string
     * @since 1.1-sofia
     */
    private $token;
    
    /**
     * The token secret
     * @var string
     * @since 1.1-sofia
     */
    private $tokenSecret;
    
    /**
     * Get or set response token
     * @param string $token (optional) Set the token
     * @return string Returns the token
     * @since 1.1-sofia
     */
    public function token($token = null){
        if(func_num_args() == 1){
            $this->token = $token;
        }
        return $this->token;
    }

    /**
     * Get or set response token secret
     * @param string $tokenSecret (optional) Set the token secret
     * @return string Returns the token secret
     * @since 1.1-sofia
     */
    public function tokenSecret($tokenSecret = null){
        if(func_num_args() == 1){
            $this->tokenSecret = $tokenSecret;
        }
        return $this->tokenSecret;
    }

    
}