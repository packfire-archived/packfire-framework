<?php
pload('packfire.net.http.pHttpServer');
pload('pOAuth');
pload('pOAuthRequest');
pload('pOAuthHelper');
pload('pOAuthResponse');
pload('pOAuthToken');

/**
 * pOAuthConsumer class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth
 * @since 1.1-sofia
 */
class pOAuthConsumer {
    
    /**
     * The consumer key
     * @var string
     * @since 1.1-sofia
     */
    private $key;
    
    /**
     * The secret key
     * @var string
     * @since 1.1-sofia
     */
    private $secret;
    
    /**
     * Last token secret returned by the service provider
     * @var string
     * @since 1.1-sofia
     */
    private $tokenSecret;
    
    /**
     * The callback URL for the consumer
     * @var string|pUrl
     * @since 1.1-sofia
     */
    private $callback;
    
    /**
     * The signature that the consumer will use to sign the requests
     * @var pOAuthSignature
     */
    private $signatureMethod;
    
    /**
     * Create a new pOAuthConsumer object
     * @param string $key The consumer key
     * @param string $secret The secret key
     * @param string|pUrl $callback The callback URL
     * @since 1.1-sofia
     */
    public function __construct($key, $secret, $callback, $signatureMethod = null){
        $this->key = $key;
        $this->secret = $secret;
        $this->callback = $callback;
        if(!$signatureMethod){
            $signatureMethod = 'pOAuthHmacSha1Signature';
        }
        $this->signatureMethod = $signatureMethod;
    }
    
    /**
     * Get the consumer key
     * @return string Returns the consumer key
     * @since 1.1-sofia
     */
    public function key(){
        return $this->key;
    }
    
    /**
     * Get the consumer secret key
     * @return string Returns the secret key
     * @since 1.1-sofia
     */
    public function secret(){
        return $this->secret;
    }
    
    /**
     * Get the callback URL of the consumer
     * @return string|pUrl Returns the callback URL
     * @since 1.1-sofia
     */
    public function callback(){
        return $this->callback;
    }
    
    /**
     * Create and prepare a request for the consumer
     * @return pOAuthRequest Returns the prepared request object
     * @since 1.1-sofia
     */
    private function createRequest(){
        $request = new pOAuthRequest();
        $request->method('GET');
        $request->oauth(pOAuth::CONSUMER_KEY, $this->key);
        $request->oauth(pOAuth::VERSION, '1.0');
        return $request;
    }
    
    /**
     * Request the service provider for a request token
     * @param pUrl|string $url The URL of the end point to request the
     *                  request token from.
     * @return pOAuthToken Returns the token provided by the service provider
     * @since 1.1-sofia
     */
    public function requestTokenRequest($url){
        if(!($url instanceof pUrl)){
            $url = new pUrl($url);
        }
        $server = new pHttpServer($url->host(), $url->port());
        $request = $this->createRequest();
        $request->get()->append($url->params());
        $request->headers()->add('Host',
                $url->host() . ($url->port() == 80 ? '' : ':' . $url->port()));
        $request->uri($url->path());
        $request->oauth(pOAuth::NONCE, pOAuthHelper::generateNonce(__METHOD__));
        $request->sign($this->signatureMethod, $this, $this->tokenSecret);
        $response = $server->request($request, new pOAuthResponse());
        /* @var $response pOAuthResponse */
        $this->tokenSecret = $response->oauth(pOAuth::TOKEN_SECRET);
        return pOAuthToken::load($response);
    }
    
    /**
     * Request the service provider for an access token
     * @param pUrl|string $url The URL of the end point to request the
     *                  access token from.
     * @return pOAuthToken Returns the token provided by the service provider
     * @since 1.1-sofia
     */
    public function accessTokenRequest($url, $requestToken){
        if(!($url instanceof pUrl)){
            $url = new pUrl($url);
        }
        $server = new pHttpServer($url->host(), $url->port());
        $request = $this->createRequest();
        $request->get()->append($url->params());
        $request->headers()->add('Host',
                $url->host() . ($url->port() == 80 ? '' : ':' . $url->port()));
        $request->uri($url->path());
        $request->oauth(pOAuth::TOKEN, (string)$requestToken);
        $request->oauth(pOAuth::NONCE, pOAuthHelper::generateNonce(__METHOD__));
        $request->sign($this->signatureMethod, $this, $this->tokenSecret);
        $response = $server->request($request, new pOAuthResponse());
        /* @var $response pOAuthResponse */
        $this->tokenSecret = $response->oauth(pOAuth::TOKEN_SECRET);
        return pOAuthToken::load($response);
    }
    
    /**
     * Access the resources securely with an access token granted by the 
     *      service provider.
     * @param pUrl|string $url The URL to access the server resources
     * @param pOAuthToken|string $accessToken The access token
     * @return pOAuthResponse The response from the server
     * @since 1.1-sofia
     */
    public function accessResource($url, $accessToken){
        if(!($url instanceof pUrl)){
            $url = new pUrl($url);
        }
        $server = new pHttpServer($url->host(), $url->port());
        $request = $this->createRequest();
        $request->get()->append($url->params());
        $request->headers()->add('Host',
                $url->host() . ($url->port() == 80 ? '' : ':' . $url->port()));
        $request->uri($url->path());
        $request->oauth(pOAuth::TOKEN, (string)$accessToken);
        $request->oauth(pOAuth::NONCE, pOAuthHelper::generateNonce(__METHOD__));
        $request->sign($this->signatureMethod, $this, $this->tokenSecret);
        $response = $server->request($request, new pOAuthResponse());
        /* @var $response pOAuthResponse */
        return $response;
    }
    
}