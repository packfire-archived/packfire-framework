<?php

/**
 * pOAuthToken class
 * 
 * A token
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth.token
 * @since 1.1-sofia
 */
class pOAuthToken {
    
    /**
     * The token identifier
     * @var string
     * @since 1.1-sofia
     */
    private $key;
    
    /**
     * The token secret
     * @var string
     */
    private $secret;
    
    public function __construct($key, $secret){
        $this->key = $key;
        $this->secret = $secret;
    }
    
    /**
     * Get the token's identifier
     * @return string Returns the identifier
     * @since 1.1-sofia
     */
    public function key(){
        return $this->key;
    }
    
    /**
     * Get the token secret
     * @return string Returns the secret. Keep it secret for later~ (:
     * @since 1.1-sofia
     */
    public function secret(){
        return $this->secret;
    }
    
    /**
     * Get the token information from the Service provider OAuth response
     * @param pOAuthResponse $response The response to load from
     * @return pOAuthToken Returns the token information
     * @since 1.1-sofia
     */
    public static function load($response){
        return new self($response->oauth(pOAuth::TOKEN),
                $response->oauth(pOAuth::TOKEN_SECRET));
    }
    
    /**
     * Casting aid
     * @internal
     * @ignore
     * @since 1.1-sofia
     */
    public function __toString(){
        return $this->key;
    }
    
}