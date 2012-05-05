<?php
pload('packfire.net.pHttpResponse');

/**
 * A Response that indicates that the response is JSON
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.response
 * @since 1.0-sofia
 */
class pJsonResponse extends pHttpResponse {
    
    /**
     * Create a new pJsonResponse object
     * @param mixed $object The JSON object that will be responded to the
     *                      client with
     * @since 1.0-sofia
     */
    public function __construct($object){
        parent::__construct();
        $this->headers()->add('Content-Type', 'application/json');
        if(is_string($object)){ // probably already encoded
            $this->body($object); 
        }else{
            $this->body(json_encode($object));
        }
    }
    
}