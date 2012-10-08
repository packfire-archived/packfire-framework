<?php
namespace Packfire\Exception;

use Exception;
use Packfire\Net\Http\HttpResponseCode;

/**
 * HttpException class
 * 
 * A HTTP Exception
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception
 * @since 1.0-sofia
 */
class HttpException extends Exception {
    
    /**
     * Create a new HttpException object
     * @param integer $httpCode The HTTP code
     * @param string $message (optional) Additional exception message
     * @since 1.0-sofia
     */
    public function __construct($httpCode, $message = null){
        $http = constant('HttpResponseCode::HTTP_' . $httpCode);
        $this->responseCode = $httpCode;
        parent::__construct($http . ($message ? ' ' . $message : ''),
                $httpCode);
    }
    
}