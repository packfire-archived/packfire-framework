<?php
pload('packfire.application.http.pHttpAppResponse');
pload('packfire.data.serialization.pJsonSerializer');
pload('IResponseFormat');

/**
 * pJsonResponse class
 * 
 * A Response that indicates that the response is JSON
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.response
 * @since 1.0-sofia
 */
class pJsonResponse extends pHttpAppResponse implements IResponseFormat {
    
    /**
     * Create a new pJsonResponse object
     * @param mixed $object The JSON object that will be responded to the
     *                      client with
     * @param string $callback (optional) The callback for JSONP calls
     * @since 1.0-sofia
     */
    public function __construct($object, $callback = null){
        parent::__construct();
        $serializer = new pJsonSerializer();
        if($callback){
            $this->headers()->add('Content-Type', 'text/javascript');
            $this->body($callback . '(' . $serializer->serialize($object) . ')');
        }else{
            $this->headers()->add('Content-Type', 'application/json');
            $this->body($serializer->serialize($object));
        }
    }
    
}