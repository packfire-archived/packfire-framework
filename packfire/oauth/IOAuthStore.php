<?php

/**
 * pOAuthStore interface
 * 
 * OAuth Data Storage interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth
 * @since 1.1-sofia
 */
interface IOAuthStore {
    
    /**
     * Get the consumer object by its consumer key
     * @param string $key The consumer key of the consumer
     * @return pOAuthConsumer Returns the consumer object
     * @since 1.1-sofia
     */
    public function getConsumer($key);
    
    /**
     * Checks if the request token is valid
     * @param pOAuthConsumer $consumer The consumer which has the request token.
     * @param string $requestToken The request token to check validity
     * @return pOAuthToken Returns the token if the token is valid, false otherwise.
     * @since 1.1-sofia
     */
    public function getRequestToken($consumer, $requestToken);
    
    /**
     * Revokes the validity of the request token
     * @param pOAuthConsumer $consumer The consumer which has the request token.
     * @param string $requestToken The request token to be revoked of its validity.
     * @since 1.1-sofia
     */
    public function revokeRequestToken($consumer, $requestToken);
    
    /**
     * Creates a new request token and store 
     * @param pOAuthConsumer $consumer The consumer which will hold the request token.
     * @param string $callback (optional) The callback URL provided by the consumer
     * @return pOAuthToken Returns the request token created with its secret
     * @since 1.1-sofia
     */
    public function createRequestToken($consumer, $callback = null);
    
    /**
     * Get the callback URL / string of a consumer based on the token
     * @param pOAuthConsumer $consumer The consumer to get callback URL
     * @param string $token (optional) The token which the callback URL is assigned with
     * @return string Returns the callback URL / string
     * @since 1.1-sofia
     */
    public function getCallback($consumer, $token = null);
    
    /**
     * Checks if the access token is valid
     * @param pOAuthConsumer $consumer The consumer which has the access token.
     * @param string $requestToken The request token to check validity
     * @param string $verifier (optional) The optional verifier
     * @return pOAuthToken Returns the token if the token is valid, false otherwise.
     * @since 1.1-sofia
     */
    public function getAccessToken($consumer, $accessToken, $verifier = null);
    
    /**
     * Revokes the validity of the access token
     * @param pOAuthConsumer $consumer The consumer which has the request token.
     * @param string $requestToken The request token to be revoked of its validity.
     * @since 1.1-sofia
     */
    public function revokeAccessToken($consumer, $accessToken);
    
    /**
     * Grant an access token over a request token
     * @param pOAuthConsumer $consumer The consumer which will hold the access token.
     * @param pOAuthToken $requestToken The request token
     * @param string $verifier (optional) The optional verifier
     * @return pOAuthToken Returns the access token granted with the secret if
     *              the consumer has been approved by the user, or NULL if the
     *              consumer has been rejected.
     * @since 1.1-sofia
     */
    public function grantAccessToken($consumer, $requestToken, $verifier = null);
    
    /**
     * Check if a nonce is valid based on the value combinations
     * @param pOAuthConsumer $consumer The consumer making the request
     * @param pOAuthToken $token The token of the request
     * @param string $timestamp The timestamp of the request
     * @param string $nonce The nonce value to be checked.
     * @since 1.1-sofia
     */
    public function checkNonce($consumer, $token, $timestamp, $nonce);
    
    /**
     * Store a new nonce value and its combination
     * @param pOAuthConsumer $consumer The consumer
     * @param pOAuthToken $token The token 
     * @param string $timestamp The timestamp of the request
     * @param string $nonce The nonce to be stored
     * @since 1.1-sofia
     */
    public function storeNonce($consumer, $token, $timestamp, $nonce);
    
}