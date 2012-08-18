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
 * @package packfire.oauth.request
 * @since 1.1-sofia
 */
abstract class pOAuthRequest extends pHttpAppRequest {
    
    /**
     * The OAuth parameters
     * @var pMap
     * @since 1.1-sofia
     */
    private $oauthParams;
    
    public function __construct($client, $server) {
        parent::__construct($client, $server);
        $this->oauthParams = new pMap();
        $this->oauthParams->add(pOAuth::VERSION, '1.0')
    }
    
    public function oauth($key, $value = null){
        if(func_num_args() == 2){
            return $this->oauthParams->add($key, $value);
        }else{
            return $this->oauthParams->get($key);
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
    
}