<?php
pload('pOAuthSignature');
pload('pOAuthResponse');
pload('pOAuth');
pload('pOAuthException');
pload('packfire.net.http.pHttpRequest');
pload('pOAuthRequest');

/**
 * pOAuthProvider class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth
 * @since 1.1-sofia
 */
class pOAuthProvider {
    
    /**
     *
     * @var IOAuthStore
     */
    private $store;
    
    /**
     * 
     * @param IOAuthStore $store
     * @since 1.1-sofia
     */
    public function __construct($store){
        $this->store = $store;
    }
    
    /**
     * 
     * @param pOAuthRequest $request
     */
    protected function verifyRequest($request, $consumer, $tokenSecret = null){
        $sigMethod = pOAuthSignature::load($request->oauth(pOAuth::SIGNATURE_METHOD));
        $signer = new $sigMethod($request, $consumer, $tokenSecret);
        /* @var $signer pOAuthSignature */
        if(!$signer->check($request->oauth(pOAuth::SIGNATURE))){
            throw new pOAuthException('Invalid Request Signature provided');
        }
    }
    
    protected function checkNonce($request, $consumer, $token = null){
        $timestamp = $request->oauth(pOAuth::TIMESTAMP);
        $nonce = $request->oauth(pOAuth::NONCE);
        if(!$this->store->checkNonce($consumer, $token, $timestamp, $nonce)){
            throw new pOAuthException('Replay detected through nonce.');
        }
        $this->store->storeNonce($consumer, $token, $timestamp, $nonce);
    }
    
    public function grantRequestToken($request){
        if($request instanceof pHttpRequest && !($request instanceof pOAuthRequest)){
            $reloaded = new pOAuthRequest();
            $reloaded->preload($request);
            $request = $reloaded;
            unset($reloaded);
        }
        $consumer = $this->store->getConsumer($request->oauth(pOAuth::CONSUMER_KEY));
        if(!$consumer){
            throw new pOAuthException('No consumer found based on request consumer key');
        }
        $this->verifyRequest($request, $consumer);
        $this->checkNonce($request, $consumer);
        
        $token = $this->store->createRequestToken($consumer);
        $response = new pOAuthResponse();
        $token->assign($response);
        return $response;
    }
    
    public function grantAccessToken($request, $verifier = null){
        if($request instanceof pHttpRequest && !($request instanceof pOAuthRequest)){
            $reloaded = new pOAuthRequest();
            $reloaded->preload($request);
            $request = $reloaded;
            unset($reloaded);
        }
        $consumer = $this->store->getConsumer($request->oauth(pOAuth::CONSUMER_KEY));
        if(!$consumer){
            throw new pOAuthException('No consumer found based on request consumer key');
        }
        $requestToken = $this->store->getRequestToken($consumer, $request->oauth(pOAuth::TOKEN));
        if(!$requestToken){
            throw new pOAuthException('Request Token is invalid');
        }
        $this->verifyRequest($request, $consumer, $requestToken->secret());
        $this->checkNonce($request, $consumer, $requestToken);
        
        $token = $this->store->grantAccessToken($consumer, $requestToken, $verifier);
        if($token){
            $response = new pOAuthResponse();
            $token->assign($response);
            return $response;
        }else{
            throw new pOAuthException('Access denied because request token was not granted.');
        }
    }
    
    public function verify($request){
        if($request instanceof pHttpRequest && !($request instanceof pOAuthRequest)){
            $reloaded = new pOAuthRequest();
            $reloaded->preload($request);
            $request = $reloaded;
            unset($reloaded);
        }
        $consumer = $this->store->getConsumer($request->oauth(pOAuth::CONSUMER_KEY));
        if(!$consumer){
            throw new pOAuthException('No consumer found based on request consumer key');
        }
        $accessToken = $this->store->getAccessToken($consumer, $request->oauth(pOAuth::TOKEN), $request->oauth(pOAuth::VERIFIER));
        if(!$accessToken){
            throw new pOAuthException('Access denied because token token was not granted.');
        }
        
        $this->verifyRequest($request, $consumer, $accessToken->secret());
        $this->checkNonce($request, $consumer, $accessToken);
    }
    
}