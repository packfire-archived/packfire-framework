<?php
pload('pOAuthSignature');
pload('pOAuthResponse');
pload('pOAuth');
pload('pOAuthException');
use Packfire\Net\Http\Request as HttpRequest;
pload('pOAuthRequest');

/**
 * pOAuthProvider class
 * 
 * The service provider functionality of OAuth
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth
 * @since 1.1-sofia
 */
class pOAuthProvider extends pBucketUser {
    
    /**
     * The data storage
     * @var IOAuthStore
     * @since 1.1-sofia
     */
    private $store;
    
    /**
     * The amount of time allowed for timestamp differences
     * @var integer
     * @since 1.1-sofia
     */
    private $timeout;
    
    /**
     * The consumer accessing the provider
     * @var pOAuthConsumer
     * @since 1.1-sofia
     */
    private $consumer;
    
    /**
     * Create a new pOAuthProvider object
     * @param IOAuthStore $store (optional) The data storage for OAuth. If not set,
     *          the store will be retrieved from the 'oauth.store' IoC service.
     * @param integer $timeout (optional) The amount of time allowance for timestamps. 
     *          If not set, any timestamps will be allowed.
     * @since 1.1-sofia
     */
    public function __construct($store = null, $timeout = null){
        $this->store = $store;
        $this->timeout = $timeout;
    }
    
    /**
     * Get the data storage
     * @return IOAuthStore The data storage
     * @since 1.1-sofia
     */
    protected function store(){
        if(!$this->store){
            $this->store = $this->service('oauth.store');
        }
        return $this->store;
    }
    
    /**
     * Get the consumer that last accessed the provider.
     * @return pOAuthConsumer Returns the consumer if available or null if not.
     * @since 1.1-sofia
     */
    public function consumer(){
        return $this->consumer;
    }
    
    /**
     * Verify if a request signature is valid
     * @param pOAuthRequest $request The request made by the client
     * @param string $tokenSecret (optional) The token secret if any
     * @throws pOAuthException
     * @since 1.1-sofia
     */
    protected function verifyRequest($request, $tokenSecret = null){
        $sigMethod = pOAuthSignature::load($request->oauth(pOAuth::SIGNATURE_METHOD));
        $signer = new $sigMethod($request, $this->consumer, $tokenSecret);
        /* @var $signer pOAuthSignature */
        if(!$signer->check($request->oauth(pOAuth::SIGNATURE))){
            throw new pOAuthException('Invalid Request Signature provided');
        }
    }
    
    /**
     * Check nonce for replay attacks
     * @param pOAuthRequest $request The request made by the client
     * @param pOAuthToken $token (optional) The token if provided
     * @throws pOAuthException Thrown when a duplicated entry is found in the store.
     * @since 1.1-sofia
     */
    protected function checkNonce($request, $token = null){
        $timestamp = $request->oauth(pOAuth::TIMESTAMP);
        $nonce = $request->oauth(pOAuth::NONCE);
        if(!$this->store()->checkNonce($this->consumer, $token, $timestamp, $nonce)){
            throw new pOAuthException('Replay detected through nonce.');
        }
        $this->store()->storeNonce($this->consumer, $token, $timestamp, $nonce);
    }
    
    /**
     * Check if a timestamp provided by the client is valid
     * @param integer $time The unix epoch timestamp provided by the client
     * @throws pOAuthException Thrown when the timestamp has expired or far in the future.
     * @since 1.1-sofia
     */
    protected function checkTimestamp($time){
        if($this->timeout && abs(time() - $time) > $this->timeout){
            throw new pOAuthException('The request has expired. Timestamp mismatched.');
        }
    }
    
    /**
     * Grant the client a request token based on the request made by the client.
     * @param HttpRequest $request The request made by the client
     * @return pOAuthResponse Returns the OAuth HTTP response if request token is granted.
     * @throws pOAuthException Thrown when the request fails any of the OAuth standard verification.
     * @since 1.1-sofia
     */
    public function grantRequestToken($request){
        if($request instanceof HttpRequest && !($request instanceof pOAuthRequest)){
            $reloaded = new pOAuthRequest();
            $reloaded->preload($request);
            $request = $reloaded;
            unset($reloaded);
        }
        $this->checkTimestamp($request->oauth(pOAuth::TIMESTAMP));
        $this->consumer = $this->store()->getConsumer($request->oauth(pOAuth::CONSUMER_KEY));
        if(!$this->consumer){
            throw new pOAuthException('No consumer found based on request consumer key');
        }
        $this->verifyRequest($request);
        $this->checkNonce($request);
        
        $token = $this->store()->createRequestToken($this->consumer);
        $response = new pOAuthResponse();
        $token->assign($response);
        return $response;
    }
    
    /**
     * Grant the client an access token based on the request made by the client.
     * @param HttpRequest $request The request made by the client
     * @param string $verifier (optional) Set a verifier to the access token for checking later.
     * @return pOAuthResponse Returns the OAuth HTTP response if access token is granted.
     * @throws pOAuthException Thrown when the request fails any of the OAuth standard verification.
     * @since 1.1-sofia
     */
    public function grantAccessToken($request, $verifier = null){
        if($request instanceof HttpRequest && !($request instanceof pOAuthRequest)){
            $reloaded = new pOAuthRequest();
            $reloaded->preload($request);
            $request = $reloaded;
            unset($reloaded);
        }
        $this->checkTimestamp($request->oauth(pOAuth::TIMESTAMP));
        $this->consumer = $this->store()->getConsumer($request->oauth(pOAuth::CONSUMER_KEY));
        if(!$this->consumer){
            throw new pOAuthException('No consumer found based on request consumer key');
        }
        $requestToken = $this->store()->getRequestToken($this->consumer, $request->oauth(pOAuth::TOKEN));
        if(!$requestToken){
            throw new pOAuthException('Request Token is invalid');
        }
        $this->verifyRequest($request, $requestToken->secret());
        $this->checkNonce($request, $requestToken);
        
        $token = $this->store()->grantAccessToken($this->consumer, $requestToken, $verifier);
        if($token){
            $response = new pOAuthResponse();
            $token->assign($response);
            return $response;
        }else{
            throw new pOAuthException('Access denied because request token was not granted.');
        }
    }
    
    /**
     * Verify if a request made to a protected resource is valid.
     * @param HttpRequest $request The request made by the client
     * @return pOAuthRequest Returns the OAuth request processed and verified.
     * @throws pOAuthException Thrown when the request fails any of the OAuth standard verification.
     * @since 1.1-sofia
     */
    public function verify($request){
        if($request instanceof HttpRequest && !($request instanceof pOAuthRequest)){
            $reloaded = new pOAuthRequest();
            $reloaded->preload($request);
            $request = $reloaded;
            unset($reloaded);
        }
        $this->checkTimestamp($request->oauth(pOAuth::TIMESTAMP));
        $this->consumer = $this->store()->getConsumer($request->oauth(pOAuth::CONSUMER_KEY));
        if(!$this->consumer){
            throw new pOAuthException('No consumer found based on request consumer key');
        }
        $accessToken = $this->store()->getAccessToken($this->consumer, $request->oauth(pOAuth::TOKEN), $request->oauth(pOAuth::VERIFIER));
        if(!$accessToken){
            throw new pOAuthException('Access denied because access token was not granted.');
        }
        
        $this->verifyRequest($request, $accessToken->secret());
        $this->checkNonce($request, $accessToken);
        return $request;
    }
    
}