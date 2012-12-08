<?php
namespace Packfire\OAuth;

use Packfire\Net\Http\Request as HttpRequest;
use Packfire\Net\Http\Method as HttpMethod;
use Packfire\DateTime\DateTime;
use Packfire\Collection\Map;
use Packfire\Exception\InvalidArgumentException;
use Packfire\OAuth\OAuth;
use Packfire\OAuth\Helper;
use Packfire\OAuth\Signature;
use Packfire\OAuth\IHttpEntity;

/**
 * Request class
 *
 * OAuth Request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth
 * @since 1.1-sofia
 */
class Request extends HttpRequest implements IHttpEntity {

    /**
     * The OAuth parameters
     * @var Map
     * @since 1.1-sofia
     */
    private $oauthParams;

    /**
     * Create a new Request object
     * @since 1.1-sofia
     */
    public function __construct() {
        parent::__construct();
        $this->oauthParams = new Map();
        $this->oauthParams->add(OAuth::VERSION, '1.0');
    }

    /**
     * Preload the OAuth request from another HTTP request
     * @param HttpRequest $request The request to load data from
     * @since 1.1-sofia
     */
    public function preload($request){
        $this->get = new Map($request->get);
        $this->post = new Map($request->post);
        $this->headers = new Map($request->headers);
        $this->cookies = new Map($request->cookies);
        $this->https = $request->https;
        $this->version = $request->version;
        if($request->time instanceof DateTime){
            $this->time = DateTime::fromTimestamp($request->time->toTimestamp());
        }
        $this->uri = $request->uri;
        $this->method = strtoupper($request->method);
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
            $this->get->add($key, $value);
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
        if($this->method == HttpMethod::POST){
            foreach($this->post as $key => $value){
                if(substr($key, 0, 5) == 'oauth'){
                    $this->oauthParams->add($key, $value);
                }
            }
        }
        $authHeader = $this->headers->get('authorization');
        if(is_string($authHeader) && substr($authHeader, 0, 6) == 'OAuth '){
            $params = array();
            $matches = array();
            if (preg_match_all('/(oauth[a-z_-]*)=(:?"([^"]*)"|([^,]*))/',
                    $authHeader, $matches)) {
                foreach ($matches[1] as $i => $key) {
                    $params[$key] = Helper::urldecode(
                        empty($matches[3][$i]) ? $matches[4][$i] : $matches[3][$i]
                    );
                }
            }
            $this->oauthParams->append($params);
        }elseif($authHeader){
            throw new OAuthException(
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
        $params = new Map($this->get);

        if(!$this->method() == HttpMethod::POST){
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

        $url = $this->url();
        $url->params(array()); // no GET parameters in URL for base signature

        $headerData = array($this->method, (string)$url);

        $pData = array();
        foreach($kparams as $key => $value){
            $pData[] = sprintf('%s=%s', $key, $value);
        }
        $headerData[] = implode('&', $pData);

        return implode('&', Helper::urlencode($headerData)) ;
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
     * Build the authorization header
     * @return string Returns the constructed header string value
     * @since 1.1-sofia
     */
    public function buildAuthorizationHeader(){
        $params = array();
        foreach ($this->oauthParams as $key => $value) {
          if (substr($key, 0, 5) == 'oauth') {
            $params[] = Helper::urlencode($key) .
                    '="' .
                    Helper::urlencode($value) .
                    '"';
          }
        }
        return 'OAuth ' . implode(', ', $params);
    }

    /**
     * Sign this request for OAuth interaction
     * @param string $method The name of the method to use to sign this request
     * @param Consumer $consumer The consumer making the request
     * @param string $tokenSecret (optional) The token secret provided by the OAuth provider
     * @throws InvalidArgumentException Thrown when $method signature method is invalid
     * @since 1.1-sofia
     */
    public function sign($method, $consumer, $tokenSecret = null){
        $sigMethod = Signature::load($method);
        if(!$sigMethod){
            throw new InvalidArgumentException(
                    'Request::sign',
                    'method',
                    'the name of a valid OAuth signature method',
                    $method
                );
        }
        /* @var $signer Signature */
        $signer = new $sigMethod($this, $consumer, $tokenSecret);
        $this->oauth(OAuth::SIGNATURE_METHOD, $signer->name());
        $this->oauth(OAuth::TIMESTAMP, time());
        $this->oauth(OAuth::SIGNATURE, $signer->build());
    }

}