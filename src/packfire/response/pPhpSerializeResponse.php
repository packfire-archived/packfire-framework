<?php
pload('IResponseFormat');
pload('packfire.application.http.pHttpAppResponse');

/**
 * pPhpSerializeResponse class
 * 
 * A response that serializes the object
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.response
 * @since 1.1-sofia
 */
class pPhpSerializeResponse extends pHttpAppResponse implements IResponseFormat {
    
    /**
     * Create a new pPhpSerializeResponse object
     * @param mixed $object The object that will be encoded and sent to the
     *                      client
     * @since 1.1-sofia
     */
    public function __construct($object) {
        parent::__construct();
        $this->headers()->add('Content-Type', 'text/plain');
        $this->body(serialize($object));
    }

}