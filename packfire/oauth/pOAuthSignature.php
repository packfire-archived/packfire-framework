<?php

/**
 * pOAuthSignature class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth
 * @since 1.1-sofia
 */
abstract class pOAuthSignature {
    
    /**
     * The request parameters that was used
     * @var pOAuthRequest
     * @since 1.1-sofia
     */
    protected $request;
    
    /**
     * The OAuth consumer
     * @var pOAuthConsumer
     * @since 1.1-sofia
     */
    protected $consumer;
    
    /**
     * The token secret provided by the OAuth provider
     * @var string
     * @since 1.1-sofia
     */
    protected $tokenSecret;
    
    /**
     * Create a new pOAuthSignature object
     * @param pOAuthRequest $request The request that uses this signature generation
     * @param pOAuthConsumer $consumer The consumer
     * @param string $tokenSecret The token secret provided by the OAuth provider
     * @since 1.1-sofia
     */
    public function __construct($request, $consumer, $tokenSecret = null){
        $this->request = $request;
        $this->consumer = $consumer;
        $this->tokenSecret = $tokenSecret;
    }
    
    public static function load($name){
        $registry = new pMap();
        $registry->add('HMAC-SHA1', 'pOAuthHmacSha1Signature');
        $registry->add('PLAINTEXT', 'pOAuthPlainTextSignature');
        if(substr($name, 0, 6) != 'pOAuth'){
            $name = $registry->get($name);
        }
        pload('packfire.oauth.signature.' . $name);
        return $name;
    }
    
    /**
     * Get the name of this signature method
     * @return string Returns the name of the signature method
     * @since 1.1-sofia
     */
    abstract public function name();
    
    /**
     * Build the signature based on the parameters
     * @return string Returns the signature built
     * @since 1.1-sofia
     */
    abstract public function build();
    
    /**
     * Check if a signature is valid based on the parameters provided.
     * @param string $signature
     * @return boolean Returns true if the signature is valid, false otherwise.
     * @since 1.1-sofia 
     */
    public function check($signature){
        return $this->build() == $signature;
    }
    
}