<?php
pload('pOAuthSignature');
pload('pOAuthResponse');
pload('pOAuth');
pload('pOAuthException');

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
    
    protected function checkNonce($request, $consumer){
        $timestamp = $request->oauth(pOAuth::TIMESTAMP);
        $nonce = $request->oauth(pOAuth::NONCE);
        $token = (string)$request->oauth(pOAuth::TOKEN);
        if(!$this->store->checkNonce($consumer, $token, $timestamp, $nonce)){
            throw new pOAuthException('Replay detected through nonce.');
        }
        $this->store->storeNonce($consumer, $token, $timestamp, $nonce);
    }
    
    public function grantRequestToken($request){
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
    
    public function grantAccessToken($request){
        $consumer = $this->store->getConsumer($request->oauth(pOAuth::CONSUMER_KEY));
        if(!$consumer){
            throw new pOAuthException('No consumer found based on request consumer key');
        }
        $this->verifyRequest($request, $consumer);
        $this->checkNonce($request, $consumer);
        $requestToken = pOAuthToken::load($request);
        
        $requestStatus = $this->store->checkRequestToken($consumer, $requestToken);
        if(!$requestStatus){
            throw new pOAuthException('Request Token is invalid');
        }
        
        $token = $this->store->grantAccessToken($consumer, $requestToken);
        if($token){
            $response = new pOAuthResponse();
            $token->assign($response);
            return $response;
        }else{
            throw new pOAuthException('Access denied because request token was not granted.');
        }
    }
    
    public function verify($request){
        $consumer = $this->store->getConsumer($request->oauth(pOAuth::CONSUMER_KEY));
        if(!$consumer){
            throw new pOAuthException('No consumer found based on request consumer key');
        }
        $this->verifyRequest($request, $consumer);
        $this->checkNonce($request, $consumer);
        $accessToken = pOAuthToken::load($request);
        
        $accessStatus = $this->store->checkAccessToken($consumer, $accessToken, $request->oauth(pOAuth::VERIFIER));
        if(!$accessStatus){
            throw new pOAuthException('Access denied because token token was not granted.');
        }
    }
    
}