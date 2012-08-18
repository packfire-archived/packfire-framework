<?php
pload('packfire.net.http.pHttpRequest');

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
abstract class pOAuthRequest extends pHttpRequest {
    
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
        
        foreach($this->get() as $key => $value){
            if(substr($key, 0, 6) == 'oauth_'){
                $this->oauthParams->add($key, $value);
            }
        }
        if($this->method() == pHttpMethod::POST){
            foreach($this->post() as $key => $value){
                if(substr($key, 0, 6) == 'oauth_'){
                    $this->oauthParams->add($key, $value);
                }
            }
        }
        
        $authHeader = $this->headers()->get('Authorization');
        if(substr($authHeader, 0, 6) == 'OAuth '){
            $params = array();
            $matches = array();
            if (preg_match_all('/(oauth_[a-z_-]*)=(:?"([^"]*)"|([^,]*))/', $authHeader, $matches)) {
                foreach ($matches[1] as $i => $h) {
                    $params[$h] = urldecode(empty($matches[3][$i]) ? $matches[4][$i] : $matches[3][$i]);
                }
            }
            $this->oauthParams->append($params);
        }
    }
    
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
            parent::method($m);
        }
        return strtoupper(parent::method());
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
    
}