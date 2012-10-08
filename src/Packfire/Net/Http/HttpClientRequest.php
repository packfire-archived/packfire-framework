<?php
namespace Packfire\Net\Http;

use HttpRequest;

/**
 * HttpClientRequest class
 * 
 * A client's HTTP request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Net\Http
 * @since 1.0-sofia
 */
class HttpClientRequest extends HttpRequest {
    
    /**
     * The client that made this request
     * @var HttpClient
     * @since 1.0-sofia
     */
    private $client;
    
    /**
     * Create a new HttpClientRequest object
     * @param HttpClient $client The client that requested this HTTP Request
     * @since 1.0-sofia
     */
    function __construct($client) {
        parent::__construct();
        $this->client = $client;
    }
    
    /**
     * Get the HttpClient that requested this request
     * @return HttpClient Returns the client
     * @since 1.0-sofia
     */
    public function client(){
        return $this->client;
    }
    
}