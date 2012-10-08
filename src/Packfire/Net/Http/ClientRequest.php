<?php
namespace Packfire\Net\Http;

use Request;

/**
 * ClientRequest class
 * 
 * A client's HTTP request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Net\Http
 * @since 1.0-sofia
 */
class ClientRequest extends Request {
    
    /**
     * The client that made this request
     * @var Client
     * @since 1.0-sofia
     */
    private $client;
    
    /**
     * Create a new ClientRequest object
     * @param Client $client The client that requested this HTTP Request
     * @since 1.0-sofia
     */
    function __construct($client) {
        parent::__construct();
        $this->client = $client;
    }
    
    /**
     * Get the client that requested this request
     * @return Client Returns the client
     * @since 1.0-sofia
     */
    public function client(){
        return $this->client;
    }
    
}