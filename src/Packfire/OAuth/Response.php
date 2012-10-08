<?php
namespace Packfire\OAuth;

use Packfire\Net\Http\Response as HttpResponse;
use Packfire\Collection\Map;
use Packfire\Application\IAppResponse;
use Helper;
use IHttpEntity;

/**
 * Response class
 * 
 * OAuth Response for any token requests
 *
 * @package packfire.oaut
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD Licenseh.response
 * @since 1.1-sofia
 */
class Response extends HttpResponse implements IHttpEntity, IAppResponse {

    /**
     * The OAuth parameters
     * @var Map
     * @since 1.1-sofia
     */
    private $oauthParams;
    
    /**
     * Create a new pOAuthResponse object
     * @since 1.1-sofia
     */
    public function __construct() {
        parent::__construct();
        $this->oauthParams = new Map();
        $this->headers->add('Content-Type', 'text/plain');
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
     * Convert the plain text OAuth response to a different response format
     * @param string $response The name of the IResponseFormat class to convert
     *          the OAuth response to, i.e. pJsonResponse, pXmlResponse
     * @returns IResponseFormat Returns the response created
     * @since 1.1-sofia
     */
    public function format($response){
        return new $response($this->oauthParams->toArray());
    }
    
    /**
     * Get or set the body of the OAuth response
     * @param string $body (optional) If set, the new value will be set.
     * @return string Returns the body response
     * @since 1.1-sofia
     */
    public function body($body = null){
        if(func_num_args() == 1){
            $output = array();
            parse_str(trim($body), $output);
            $this->oauthParams->append($output);
        }
        return Helper::buildQuery($this->oauthParams);
    }
    
    public function output(){
        return $this->body();
    }

}