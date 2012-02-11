<?php
Packfire::load('packfire.collection.pMap');
Packfire::load('packfire.net.http.pHttpMethod');

/**
* A URL rewrite/routing entry
*
* @author Sam-Mauris Yong / mauris@hotmail.sg
* @license http://www.opensource.org/licenses/bsd-license New BSD License
* @package packfire.routing
* @since 1.0-sofia
*/
class pRoute {
    
    /**
     * The HTTP method that this URL route will cater for. Defaults to GET.
     * @var string
     * @since 1.0-sofia
     */
    private $httpMethod = pHttpMethod::GET;

    /**
     * The rewritten relative-to-host URL
     * @var string
     * @since 1.0-sofia
     */
    private $rewrite;

    /**
     * The name of the controller class to route to
     * @var string
     * @since 1.0-sofia
     */
    private $actual;

    /**
     * The parameters in this routing
     * @var pMap
     * @since 1.0-sofia
     */
    private $params;

    /**
     * Create a new pRoute entry
     * @param string|pUrl $rewrite Rewritten version of the URL
     * @param string|pUrl $actual Name of the controller class to route to
     * @param string $method (optional) The HTTP Method to filter for, defaults to HTTP GET
     * @param pMap|array $params (optional) Parameters of the URL route
     * @since 1.0-sofia
     */
    function __construct($rewrite, $actual, $method = null, $params = array()){
        $this->rewrite = $rewrite;
        $this->actual = $actual;
        if(!$method){
            $method = pHttpMethod::GET;
        }
        $this->httpMethod = $method;
        $this->params = new pMap($params);
    }
    
    /**
     * Get the HTTP method for this URL route
     * @return string 
     */
    public function httpMethod(){
        return $this->httpMethod;
    }

    /**
     * Get the rewritten relative-to-host URL
     * @return string|pUrl
     */
    public function rewrite(){
        return $this->rewrite;
    }

    /**
     * Get the name of the controller class to route to
     * @return string
     */
    public function actual(){
        return $this->actual;
    }

    /**
     * Get the hash map of parameters for the route
     * @return pMap
     */
    public function params(){
        return $this->params;
    }
    
}