<?php
pload('packfire.oauth.http.pOAuthRequest');

/**
 * pOAuthRequestTokenRequest class
 * 
 * An OAuth request for a request token
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth.request
 * @since 1.1-sofia
 */
class pOAuthRequestTokenRequest extends pOAuthRequest {
    
    /**
     * The callback URL to send the user back in Step D
     * @var string
     * @since 1.1-sofia
     */
    private $callback;
    
    /**
     * Get or set the callback URL to send the user back in Step D
     * @param string $key (optional) Set the Access Token
     * @return string Returns the Access Token
     * @since 1.1-sofia
     */
    public function callback($callback = null){
        if(func_num_args() == 1){
            $this->callback = $callback;
        }
        return $this->callback;
    }
    
}