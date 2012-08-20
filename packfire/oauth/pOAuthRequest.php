<?php
pload('packfire.net.http.pHttpRequest');
pload('packfire.net.http.pHttpMethod');
pload('pOAuth');
pload('pOAuthHelper');
pload('pOAuthSignature');

/**
 * pOAuthRequest class
 * 
 * OAuth Request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth.request
 * @since 1.1-sofia
 */
class pOAuthRequest extends pHttpRequest {
    
    /**
     * The OAuth parameters
     * @var pMap
     * @since 1.1-sofia
     */
    private $oauthParams;
    
    /**
     * Create a new pOAuthRequest object
     * @since 1.1-sofia
     */
    public function __construct() {
        parent::__construct();
        $this->oauthParams = new pMap();
        $this->oauthParams->add(pOAuth::VERSION, '1.0');
    }
    
    /**
     * Preload the OAuth request from another HTTP request
     * @param pHttpRequest $request The request to load data from
     * @since 1.1-sofia
     */
    public function preload($request){
        $this->get = new pMap($request->get);
        $this->post = new pMap($request->post);
        $this->headers = new pMap($request->headers);
        $this->cookies = new pMap($request->cookies);
        $this->https = $request->https;
        $this->version = $request->version;
        $this->time = pDateTime::fromTimestamp($request->time->toTimestamp());
        $this->uri = $request->uri;
        $this->body = $request->body;
        $this->loadOAuthParameters();
    }
    
    /**
     * Get or set the OAuth parameters
     * @param string $key The OAuth parameter key
     * @param string $value (optional) If set, this value will be set to the key.
     * @return string Returns the value of the OAuth parameter if $value is not set.
     * @since 1.1-sofia
     */
    public function oauth($key, $value = null){
        if(func_num_args() == 2){
            $this->oauthParams->add($key, $value);
        }else{
            return $this->oauthParams->get($key);
        }
    }
    
    /**
     * Parse the string format of the HTTP request into this object
     * @param string $strRequest The string to be parsed
     * @since 1.1-sofia
     */
    public function parse($strRequest) {
        parent::parse($strRequest);
        $this->loadOAuthParameters();
    }
    
    protected function loadOAuthParameters(){
        foreach($this->get as $key => $value){
            if(substr($key, 0, 5) == 'oauth'){
                $this->oauthParams->add($key, $value);
            }
        }
        if($this->method == pHttpMethod::POST){
            foreach($this->post as $key => $value){
                if(substr($key, 0, 5) == 'oauth'){
                    $this->oauthParams->add($key, $value);
                }
            }
        }
        
        $authHeader = $this->headers->get('Authorization');
        if(substr($authHeader, 0, 6) == 'OAuth '){
            $params = array();
            $matches = array();
            if (preg_match_all('/(oauth[a-z_-]*)=(:?"([^"]*)"|([^,]*))/',
                    $authHeader, $matches)) {
                foreach ($matches[1] as $i => $key) {
                    $params[$key] = pOAuthHelper::urldecode(
                        empty($matches[3][$i]) ? $matches[4][$i] : $matches[3][$i]
                    );
                }
            }
            $this->oauthParams->append($params);
        }else{
            throw new pOAuthException(
                sprintf('Request parsed is not a valid OAuth as Authorization'
                        . ' header is not set as "OAuth", "%s" was given'
                        . ' instead.', $authHeader)
            );
        }
    }
    
    /**
     * Get the base signature of the request
     * @return string Returns the generated base signature
     * @since 1.1-sofia
     */
    public function signatureBase(){
        // Grab all parameters
        $params = new pMap($this->get);
        
        if(!$this->method() == pHttpMethod::POST){
            $params->append($this->post);
        }
        
        $params->append($this->oauthParams);

        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        if ($params->keyExists('oauth_signature')) {
            $params->removeAt('oauth_signature');
        }
        
        $kparams = $params->toArray();
        ksort($kparams);
        $headerData = array($this->method, (string)$this->url());
        $pData = array();
        foreach($kparams as $key => $value){
            $pData[] = sprintf('%s=%s', $key, $value);
        }
        $headerData[] = implode('&', $pData);
        return implode('&', pOAuthHelper::urlencode($headerData)) ;
    }
    
    /**
     * Get or set the method of the HTTP request
     * @param string $m (optional) Set the method
     * @return string Returns the method of the request
     * @since 1.1-sofia
     */
    public function method($m = null){
        if(func_num_args() == 1){
            $this->method = strtoupper($m);
        }
        return $this->method;
    }
    
    /**
     * Get the hash map of headers
     * @return pMap Returns the hash map
     * @since 1.1-sofia
     */
    public function headers() {
        $map = parent::headers();
        $map->add('Authorization', $this->buildAuthorizationHeader());
        return $map;
    }
    
    /**
     * Build the authorization header
     * @return string Returns the constructed header string value
     * @since 1.1-sofia
     */
    protected function buildAuthorizationHeader(){
        $params = array();
        foreach ($this->oauthParams as $key => $value) {
          if (substr($key, 0, 5) == 'oauth') {
            $params[] = pOAuthHelper::urlencode($key) .
                    '="' .
                    pOAuthHelper::urlencode($value) .
                    '"';
          }
        }
        return 'OAuth ' . implode(', ', $params);
    }
    
    /**
     * Sign this request for OAuth interaction
     * @param string $method The name of the method to use to sign this request
     * @param pOAuthConsumer $consumer The consumer making the request
     * @param string $tokenSecret (optional) The token secret provided by the OAuth provider
     * @since 1.1-sofia
     */
    public function sign($method, $consumer, $tokenSecret = null){
        $sigMethod = pOAuthSignature::load($method);
        if(!$sigMethod){
            throw new pInvalidArgumentException(
                sprintf('pOAuthRequest::sign() expects first parameter $method'
                        . ' to be the name of a valid OAuth signature method,'
                        . ' "%s" given instead.', $method)
            );
        }
        /* @var $signer pOAuthSignature */
        $signer = new $sigMethod($this, $consumer, $tokenSecret);
        $this->oauth(pOAuth::SIGNATURE_METHOD, $signer->name());
        $this->oauth(pOAuth::TIMESTAMP, time());
        $this->oauth(pOAuth::SIGNATURE, $signer->build());
    }
    
}