<?php
pload('pException');
pload('packfire.net.http.pHttpResponseCode');

/**
 * pHttpException class
 * 
 * A HTTP Exception
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception
 * @since 1.0-sofia
 */
class pHttpException extends pException{
    
    /**
     * Create a new pHttpException object
     * @param type $httpCode
     * @param type $message (optional)
     * @since 1.0-sofia
     */
    public function __construct($httpCode, $message = null){
        $http = constant('pHttpResponseCode::HTTP_' . $httpCode);
        $this->responseCode = $httpCode;
        parent::__construct($http . ($message ? ' ' . $message : ''),
                $httpCode);
    }
    
}