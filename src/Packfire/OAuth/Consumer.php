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

use Packfire\Net\Http\Server as HttpServer;
use Packfire\Net\Http\Url;
use Packfire\OAuth\OAuth;
use Packfire\OAuth\Request;
use Packfire\OAuth\Helper;
use Packfire\OAuth\Response;
use Packfire\OAuth\Token;

/**
 * A consumer representation of the OAuth procedure
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth
 * @since 1.1-sofia
 */
class Consumer
{
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
     * @var string|Url
     * @since 1.1-sofia
     */
    private $callback;

    /**
     * The signature that the consumer will use to sign the requests
     * @var Signature
     */
    private $signatureMethod;

    /**
     * Create a new Consumer object
     * @param string     $key      The consumer key
     * @param string     $secret   The secret key
     * @param string|Url $callback (optional) The callback URL
     * @param string|Signature (optional) The signature or the name of
     *      the signature to sign requests. If not set, HMAC-SHA1 will be
     *      used instead.
     * @since 1.1-sofia
     */
    public function __construct($key, $secret, $callback = null, $signatureMethod = null)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback = $callback;
        if (!$signatureMethod) {
            $signatureMethod = 'HmacSha1';
        }
        $this->signatureMethod = $signatureMethod;
    }

    /**
     * Get the consumer key
     * @return string Returns the consumer key
     * @since 1.1-sofia
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the consumer secret key
     * @return string Returns the secret key
     * @since 1.1-sofia
     */
    public function secret()
    {
        return $this->secret;
    }

    /**
     * Get the callback URL of the consumer
     * @return string|Url Returns the callback URL
     * @since 1.1-sofia
     */
    public function callback()
    {
        return $this->callback;
    }

    /**
     * Create and prepare a request for the consumer
     * @return Request Returns the prepared request object
     * @since 1.1-sofia
     */
    private function createRequest()
    {
        $request = new Request();
        $request->method('GET');
        $request->oauth(OAuth::CONSUMER_KEY, $this->key);
        $request->oauth(OAuth::VERSION, '1.0');

        return $request;
    }

    /**
     * Request the service provider for a request token
     * @param Url|string $url The URL of the end point to request the
     *                  request token from.
     * @return Token Returns the token provided by the service provider
     * @since 1.1-sofia
     */
    public function requestTokenRequest($url)
    {
        if (!($url instanceof Url)) {
            $url = new Url($url);
        }
        $server = new HttpServer($url->host(), $url->port());
        $request = $this->createRequest();
        $request->get()->append($url->params());
        $request->headers()->add(
            'Host',
            $url->host() . ($url->port() == 80 ? '' : ':' . $url->port())
        );
        $request->uri($url->path());
        $request->oauth(OAuth::NONCE, Helper::generateNonce(__METHOD__));
        $request->sign($this->signatureMethod, $this, $this->tokenSecret);
        $response = $server->request($request, new Response());
        /* @var $response Response */
        $this->tokenSecret = $response->oauth(OAuth::TOKEN_SECRET);

        return Token::load($response);
    }

    /**
     * Request the service provider for an access token
     * @param Url|string $url The URL of the end point to request the
     *                  access token from.
     * @return Token Returns the token provided by the service provider
     * @since 1.1-sofia
     */
    public function accessTokenRequest($url, $requestToken, $verifier = null)
    {
        if (!($url instanceof Url)) {
            $url = new Url($url);
        }
        $server = new HttpServer($url->host(), $url->port());
        $request = $this->createRequest();
        $request->get()->append($url->params());
        $request->headers()->add(
            'Host',
            $url->host() . ($url->port() == 80 ? '' : ':' . $url->port())
        );
        $request->uri($url->path());
        $request->oauth(OAuth::TOKEN, (string) $requestToken);
        $request->oauth(OAuth::NONCE, Helper::generateNonce(__METHOD__));
        if ($verifier) {
            $request->oauth(OAuth::VERIFIER, $verifier);
        }
        $request->sign($this->signatureMethod, $this, $this->tokenSecret);
        $response = $server->request($request, new Response());
        /* @var $response Response */
        $this->tokenSecret = $response->oauth(OAuth::TOKEN_SECRET);

        return Token::load($response);
    }

    /**
     * Access the resources securely with an access token granted by the
     *      service provider.
     * @param  Url|string   $url         The URL to access the server resources
     * @param  Token|string $accessToken The access token
     * @return Response     The response from the server
     * @since 1.1-sofia
     */
    public function accessResource($url, $accessToken)
    {
        if (!($url instanceof Url)) {
            $url = new Url($url);
        }
        $server = new HttpServer($url->host(), $url->port());
        $request = $this->createRequest();
        $request->get()->append($url->params());
        $request->headers()->add(
            'Host',
            $url->host() . ($url->port() == 80 ? '' : ':' . $url->port())
        );
        $request->uri($url->path());
        $request->oauth(OAuth::TOKEN, (string) $accessToken);
        $request->oauth(OAuth::NONCE, Helper::generateNonce(__METHOD__));
        $request->sign($this->signatureMethod, $this, $this->tokenSecret);
        $response = $server->request($request);
        /* @var $response Response */

        return $response;
    }
}
