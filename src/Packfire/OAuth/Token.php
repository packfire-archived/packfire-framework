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

use Packfire\OAuth\OAuth;

/**
 * A token representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth
 * @since 1.1-sofia
 */
class Token
{
    /**
     * The token identifier
     * @var string
     * @since 1.1-sofia
     */
    private $key;

    /**
     * The token secret
     * @var string
     * @since 1.1-sofia
     */
    private $secret;

    /**
     * Create a new Token object
     * @param string $key    The token identifier
     * @param string $secret The token secret
     * @since 1.1-sofia
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * Get the token's identifier
     * @return string Returns the identifier
     * @since 1.1-sofia
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the token secret
     * @return string Returns the secret. Keep it secret for later~ (:
     * @since 1.1-sofia
     */
    public function secret()
    {
        return $this->secret;
    }

    /**
     * Get the token information from the Service provider OAuth HTTP entity
     * @param  IHttpEntity $entity The entity to load from
     * @return Token       Returns the token information
     * @since 1.1-sofia
     */
    public static function load($entity)
    {
        return new self($entity->oauth(OAuth::TOKEN),
                $entity->oauth(OAuth::TOKEN_SECRET));
    }

    /**
     * Assign the token to a OAuth HTTP entity
     * @param IHttpEntity $entity The response to be assigned
     * @since 1.1-sofia
     */
    public function assign($entity)
    {
        $entity->oauth(OAuth::TOKEN, $this->key);
        $entity->oauth(OAuth::TOKEN_SECRET, $this->secret);
    }

    /**
     * Casting aid
     * @internal
     * @ignore
     * @since 1.1-sofia
     */
    public function __toString()
    {
        return (string) $this->key;
    }

}
