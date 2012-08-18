<?php
pload('packfire.response.pRedirectResponse');

/**
 * pOAuthProviderRedirectResponse class
 * 
 * This response is meant to be sent to the consumer's browser to redirect
 * the user to the service provider.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth.response
 * @since 1.1-sofia
 */
class pOAuthProviderRedirectResponse extends pRedirectResponse {
    
    /**
     * Create a new pOAuthProviderRedirectResponse
     * @param string|pUrl $url The service provider authentication URL to redirect to
     * @param string $token The access token that was granted by the service provider
     * @since 1.1-sofia
     */
    function __construct($url, $token) {
        if(!($url instanceof pUrl)){
            $url = new pUrl($url);
        }
        $url->params()->add('oauth_token', $token);
        parent::__construct($url);
    }

}