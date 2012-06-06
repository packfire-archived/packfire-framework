<?php
pload('pException');
pload('packfire.net.http.pHttpResponseCode');

/**
 * A HTTP Exception
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception
 * @since 1.0-sofia
 */
class pHttpException extends pException{
    
    public function __construct($httpCode){
        $http = constant('pHttpResponseCode::HTTP_' . $httpCode);
        parent::__construct($http,
                $httpCode);
        header('HTTP/1.1 ' . $http);
    }
    
}