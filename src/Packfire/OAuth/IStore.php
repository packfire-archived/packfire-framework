<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\OAuth;

/** 
 * OAuth Data Storage interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth
 * @since 1.1-sofia
 */
interface IStore {
    
    /**
     * Get the consumer object by its consumer key
     * @param string $key The consumer key of the consumer
     * @return Consumer Returns the consumer object
     * @since 1.1-sofia
     */
    public function getConsumer($key);
    
    /**
     * Checks if the request token is valid
     * @param Consumer $consumer The consumer which has the request token.
     * @param string $requestToken The request token to check validity
     * @return Token Returns the token if the token is valid, false otherwise.
     * @since 1.1-sofia
     */
    public function getRequestToken($consumer, $requestToken);
    
    /**
     * Revokes the validity of the request token
     * @param Consumer $consumer The consumer which has the request token.
     * @param string $requestToken The request token to be revoked of its validity.
     * @since 1.1-sofia
     */
    public function revokeRequestToken($consumer, $requestToken);
    
    /**
     * Creates a new request token and store 
     * @param Consumer $consumer The consumer which will hold the request token.
     * @param string $callback (optional) The callback URL provided by the consumer
     * @return Token Returns the request token created with its secret
     * @since 1.1-sofia
     */
    public function createRequestToken($consumer, $callback = null);
    
    /**
     * Get the callback URL / string of a consumer based on the token
     * @param Consumer $consumer The consumer to get callback URL
     * @param string $token (optional) The token which the callback URL is assigned with
     * @return string Returns the callback URL / string
     * @since 1.1-sofia
     */
    public function getCallback($consumer, $token = null);
    
    /**
     * Checks if the access token is valid
     * @param Consumer $consumer The consumer which has the access token.
     * @param string $requestToken The request token to check validity
     * @param string $verifier (optional) The optional verifier
     * @return Token Returns the token if the token is valid, false otherwise.
     * @since 1.1-sofia
     */
    public function getAccessToken($consumer, $accessToken, $verifier = null);
    
    /**
     * Revokes the validity of the access token
     * @param Consumer $consumer The consumer which has the request token.
     * @param string $requestToken The request token to be revoked of its validity.
     * @since 1.1-sofia
     */
    public function revokeAccessToken($consumer, $accessToken);
    
    /**
     * Grant an access token over a request token
     * @param Consumer $consumer The consumer which will hold the access token.
     * @param Token $requestToken The request token
     * @param string $verifier (optional) The optional verifier
     * @return Token Returns the access token granted with the secret if
     *              the consumer has been approved by the user, or NULL if the
     *              consumer has been rejected.
     * @since 1.1-sofia
     */
    public function grantAccessToken($consumer, $requestToken, $verifier = null);
    
    /**
     * Check if a nonce is valid based on the value combinations
     * @param Consumer $consumer The consumer making the request
     * @param Token $token The token of the request
     * @param string $timestamp The timestamp of the request
     * @param string $nonce The nonce value to be checked.
     * @since 1.1-sofia
     */
    public function checkNonce($consumer, $token, $timestamp, $nonce);
    
    /**
     * Store a new nonce value and its combination
     * @param Consumer $consumer The consumer
     * @param Token $token The token 
     * @param string $timestamp The timestamp of the request
     * @param string $nonce The nonce to be stored
     * @since 1.1-sofia
     */
    public function storeNonce($consumer, $token, $timestamp, $nonce);
    
}