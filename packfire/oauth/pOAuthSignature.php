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
    private $request;
    
    /**
     * The OAuth consumer
     * @var pOAuthConsumer
     * @since 1.1-sofia
     */
    private $consumer;
    
    /**
     * The response the OAuth service provider is giving back
     * @var pOAuthResponse
     * @since 1.1-sofia
     */
    private $response;
    
    /**
     * Create a new pOAuthSignature object
     * @param pOAuthRequest $request The request that uses this signature generation
     * @param pOAuthConsumer $consumer The consumer
     * @param pOAuthResponse $response The response
     * @since 1.1-sofia
     */
    public function __construct($request, $consumer, $response = null){
        $this->request = $request;
        $this->consumer = $consumer;
        $this->response = $response;
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
    
    /**
     * Get the request sent from the OAuth Consumer
     * @return pOAuthRequest Returns the request
     * @since 1.1-sofia
     */
    public function request(){
        return $this->request;
    }

    /**
     * Get the OAuth Consumer involved with the request
     * @return pOAuthConsumer Returns the consumer
     * @since 1.1-sofia
     */
    public function consumer(){
        return $this->consumer;
    }

    /**
     * Get the response the OAuth Service Provider is providing
     * @return pOAuthResponse Returns the response
     * @since 1.1-sofia
     */
    public function response(){
        return $this->response;
    }
    
}