<?php
pload('IAppResponse');

/**
 * pAppResponse class
 * 
 * A application response that stores a internal response to implement
 * decorator pattern.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
class pAppResponse implements IAppResponse {
    
    /**
     * The internal response
     * @var IAppResponse
     * @since 1.0-sofia 
     */
    protected $response;
    
    /**
     * Create a new pAppResponse
     * @param IAppResponse $response The internal response
     * @since 1.0-sofia
     */
    public function __construct($response){
        $this->response = $response->response();
    }
    
    /**
     * Get the internal response
     * @return IAppResponse Returns the internal response
     * @since 1.0-sofia
     */
    public function response(){
        return $this->response;
    }
    
}