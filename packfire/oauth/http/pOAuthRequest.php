<?php
pload('packfire.application.http.pHttpAppRequest');

/**
 * pOAuthRequest class
 * 
 * OAuth Request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth.http
 * @since 1.1-sofia
 */
abstract class pOAuthRequest extends pHttpAppRequest {
    
    /**
     * The request consumer key
     * @var string
     * @since 1.1-sofia
     */
    private $consumerKey;
    
    /**
     * The signature method to use
     * @var string
     * @since 1.1-sofia
     */
    private $signatureMethod;
    
    /**
     * The signature of the request
     * @var string
     * @since 1.1-sofia
     */
    private $signature;
    
    /**
     * Timestamp of the request
     * @var string
     * @since 1.1-sofia
     */
    private $timestamp;
    
    /**
     * Nonce of the request
     * @var string
     * @since 1.1-sofia
     */
    private $nonce;
    
    /**
     * The OAuth version
     * @var string
     * @since 1.1-sofia
     */
    private $version;
    
    /**
     * Get the base signature of the request
     * @return string Returns the generated base signature
     * @since 1.1-sofia
     */
    public function baseSignature(){
        $parts = pOAuthHelper::urlencode(array(
          $this->method(),
          (string)$this->url(),
          $this->signableParameters()
        ));

        return implode('&', $parts);
    }
    
    /**
     * Get or set the method of the HTTP request
     * @param string $m (optional) Set the method
     * @return string Returns the method of the request
     * @since 1.1-sofia
     */
    public function method($m = null){
        if(func_num_args() == 1){
            $this->method = $m;
        }
        return strtoupper($this->method);
    }
    
    /**
     * Get the parameters that can be included in the signature generation
     * @return string Returns the parameters
     * @since 1.1-sofia
     */
    protected function signableParameters() {
        // Grab all parameters
        $params = $this->params();

        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        if ($params->keyExists('oauth_signature')) {
          $params->removeAt('oauth_signature');
        }

        return http_build_query($params);
    }

    /**
     * Get or set the consumer key of the request
     * @param string $key (optional) Set the consumer key
     * @return string Returns the consumer key
     * @since 1.1-sofia
     */
    public function consumerKey($key = null){
        if(func_num_args() == 1){
            $this->consumerKey = $key;
        }
        return $this->consumerKey;
    }

    /**
     * Get or set the nonce value
     * @param string $key (optional) Set the nonce value
     * @return string Returns the nonce value
     * @since 1.1-sofia
     */
    public function nonce($nonce = null){
        if(func_num_args() == 1){
            $this->nonce = $nonce;
        }
        return $this->nonce;
    }
    /**
     * Get or set the signature of the request
     * @param string $key (optional) Set the signature
     * @return string Returns the signature
     * @since 1.1-sofia
     */
    public function signature($signature = null){
        if(func_num_args() == 1){
            $this->signature = $signature;
        }
        return $this->signature;
    }
    
    /**
     * Get or set the signing method
     * @param string $key (optional) Set the signing method
     * @return string Returns the signing method
     * @since 1.1-sofia
     */
    public function signatureMethod($method = null){
        if(func_num_args() == 1){
            $this->signatureMethod = $method;
        }
        return $this->signatureMethod;
    }
    
    /**
     * Get or set the request timestamp
     * @param string $key (optional) Set the timestamp
     * @return string Returns the timestamp
     * @since 1.1-sofia
     */
    public function timestamp($timestamp = null){
        if(func_num_args() == 1){
            $this->timestamp = $timestamp;
        }
        return $this->timestamp;
    }
    
    /**
     * Get or set the request OAuth version
     * @param string $key (optional) Set the OAuth version
     * @return string Returns the OAuth version
     * @since 1.1-sofia
     */
    public function version($version = null){
        if(func_num_args() == 1){
            $this->version = $version;
        }
        return $this->version;
    }
    
}