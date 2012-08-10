<?php

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
     * The callback URL for the consumer
     * @var string|pUrl
     * @since 1.1-sofia
     */
    private $callback;
    
    /**
     * Create a new pOAuthConsumer object
     * @param string $key The consumer key
     * @param string $secret The secret key
     * @param string|pUrl $callback The callback URL
     * @since 1.1-sofia
     */
    public function __construct($key, $secret, $callback){
        $this->key = $key;
        $this->secret = $secret;
        $this->callback = $callback;
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
    
}