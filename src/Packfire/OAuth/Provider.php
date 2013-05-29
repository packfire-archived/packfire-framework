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

use Packfire\Net\Http\Request as HttpRequest;
use Packfire\OAuth\Request;
use Packfire\OAuth\OAuth;
use Packfire\OAuth\Response;
use Packfire\OAuth\Signature;
use Packfire\OAuth\OAuthException;
use Packfire\FuelBlade\IConsumer;

/**
 * The service provider functionality of OAuth
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth
 * @since 1.1-sofia
 */
class Provider implements IConsumer
{
    /**
     * The data storage
     * @var IStore
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
     * @var Consumer
     * @since 1.1-sofia
     */
    private $consumer;

    /**
     * Create a new Provider object
     * @param IStore $store (optional) The data storage for OAuth. If not set,
     *          the store will be retrieved from the 'oauth.store' IoC service.
     * @param integer $timeout (optional) The amount of time allowance for timestamps.
     *          If not set, any timestamps will be allowed.
     * @since 1.1-sofia
     */
    public function __construct($store = null, $timeout = null)
    {
        $this->store = $store;
        $this->timeout = $timeout;
    }

    /**
     * Get the data storage
     * @return IStore The data storage
     * @since 1.1-sofia
     */
    protected function store()
    {
        return $this->store;
    }

    /**
     * Get the consumer that last accessed the provider.
     * @return Consumer Returns the consumer if available or null if not.
     * @since 1.1-sofia
     */
    public function consumer()
    {
        return $this->consumer;
    }

    /**
     * Verify if a request signature is valid
     * @param  Request        $request     The request made by the client
     * @param  string         $tokenSecret (optional) The token secret if any
     * @throws OAuthException
     * @since 1.1-sofia
     */
    protected function verifyRequest($request, $tokenSecret = null)
    {
        $sigMethod = Signature::load($request->oauth(OAuth::SIGNATURE_METHOD));
        $signer = new $sigMethod($request, $this->consumer, $tokenSecret);
        /* @var $signer Signature */
        if (!$signer->check($request->oauth(OAuth::SIGNATURE))) {
            throw new OAuthException('Invalid Request Signature provided');
        }
    }

    /**
     * Check nonce for replay attacks
     * @param  Request        $request The request made by the client
     * @param  Token          $token   (optional) The token if provided
     * @throws OAuthException Thrown when a duplicated entry is found in the store.
     * @since 1.1-sofia
     */
    protected function checkNonce($request, $token = null)
    {
        $timestamp = $request->oauth(OAuth::TIMESTAMP);
        $nonce = $request->oauth(OAuth::NONCE);
        if (!$this->store()->checkNonce($this->consumer, $token, $timestamp, $nonce)) {
            throw new OAuthException('Replay detected through nonce.');
        }
        $this->store()->storeNonce($this->consumer, $token, $timestamp, $nonce);
    }

    /**
     * Check if a timestamp provided by the client is valid
     * @param  integer        $time The unix epoch timestamp provided by the client
     * @throws OAuthException Thrown when the timestamp has expired or far in the future.
     * @since 1.1-sofia
     */
    protected function checkTimestamp($time)
    {
        if ($this->timeout && abs(time() - $time) > $this->timeout) {
            throw new OAuthException('The request has expired. Timestamp mismatched.');
        }
    }

    protected static function preloadRequest($request)
    {
        if ($request instanceof HttpRequest && !($request instanceof Request)) {
            $reloaded = new Request();
            $reloaded->preload($request);
            $request = $reloaded;
            unset($reloaded);
        }

        return $request;
    }

    /**
     * Grant the client a request token based on the request made by the client.
     * @param  HttpRequest    $request The request made by the client
     * @return Response       Returns the OAuth HTTP response if request token is granted.
     * @throws OAuthException Thrown when the request fails any of the OAuth standard verification.
     * @since 1.1-sofia
     */
    public function grantRequestToken($request)
    {
        $request = self::preloadRequest($request);
        $this->checkTimestamp($request->oauth(OAuth::TIMESTAMP));
        $this->consumer = $this->store()->getConsumer($request->oauth(OAuth::CONSUMER_KEY));
        if (!$this->consumer) {
            throw new OAuthException('No consumer found based on request consumer key');
        }
        $this->verifyRequest($request);
        $this->checkNonce($request);

        $token = $this->store()->createRequestToken($this->consumer);
        $response = new Response();
        $token->assign($response);

        return $response;
    }

    /**
     * Grant the client an access token based on the request made by the client.
     * @param  HttpRequest    $request  The request made by the client
     * @param  string         $verifier (optional) Set a verifier to the access token for checking later.
     * @return Response       Returns the OAuth HTTP response if access token is granted.
     * @throws OAuthException Thrown when the request fails any of the OAuth standard verification.
     * @since 1.1-sofia
     */
    public function grantAccessToken($request, $verifier = null)
    {
        $request = self::preloadRequest($request);
        $this->checkTimestamp($request->oauth(OAuth::TIMESTAMP));
        $this->consumer = $this->store()->getConsumer($request->oauth(OAuth::CONSUMER_KEY));
        if (!$this->consumer) {
            throw new OAuthException('No consumer found based on request consumer key');
        }
        $requestToken = $this->store()->getRequestToken($this->consumer, $request->oauth(OAuth::TOKEN));
        if (!$requestToken) {
            throw new OAuthException('Request Token is invalid');
        }
        $this->verifyRequest($request, $requestToken->secret());
        $this->checkNonce($request, $requestToken);

        $token = $this->store()->grantAccessToken($this->consumer, $requestToken, $verifier);
        if ($token) {
            $response = new Response();
            $token->assign($response);

            return $response;
        } else {
            throw new OAuthException('Access denied because request token was not granted.');
        }
    }

    /**
     * Verify if a request made to a protected resource is valid.
     * @param  HttpRequest    $request The request made by the client
     * @return Request        Returns the OAuth request processed and verified.
     * @throws OAuthException Thrown when the request fails any of the OAuth standard verification.
     * @since 1.1-sofia
     */
    public function verify($request)
    {
        $request = self::preloadRequest($request);
        $this->checkTimestamp($request->oauth(OAuth::TIMESTAMP));
        $this->consumer = $this->store()->getConsumer($request->oauth(OAuth::CONSUMER_KEY));
        if (!$this->consumer) {
            throw new OAuthException('No consumer found based on request consumer key');
        }
        $accessToken = $this->store()->getAccessToken($this->consumer,
                $request->oauth(OAuth::TOKEN),
                $request->oauth(OAuth::VERIFIER));
        if (!$accessToken) {
            throw new OAuthException('Access denied because access token was not granted.');
        }

        $this->verifyRequest($request, $accessToken->secret());
        $this->checkNonce($request, $accessToken);

        return $request;
    }

    public function __invoke($container)
    {
        $this->store = $container['oauth.store'];

        return $this;
    }

}
