<?php
pload('pHttpRequest');

/**
 * A client's HTTP request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pHttpClientRequest extends pHttpRequest {
    
    /**
     * The pHttpClient that made this request
     * @var pHttpClient
     * @since 1.0-sofia
     */
    private $client;
    
    /**
     * Create a new pHttpClientRequest object
     * @param pHttpClient $client The client that requested this HTTP Request
     * @since 1.0-sofia
     */
    function __construct($client) {
        parent::__construct();
        $this->client = $client;
    }
    
    /**
     * Get the pHttpClient that requested this request
     * @return pHttpClient Returns the client
     * @since 1.0-sofia
     */
    public function client(){
        return $this->client;
    }
    
}