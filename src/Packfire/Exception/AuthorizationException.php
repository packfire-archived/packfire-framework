<?php
namespace Packfire\Exception;

use Packfire\Exception\Exception;
use Packfire\Net\Http\ResponseCode;

/**
 * AuthorizationException class
 * 
 * Authorization exception message
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception
 * @since 1.0-sofia
 */
class AuthorizationException extends Exception {
    
    public function __construct($message, $code = null) {
        $this->responseCode = ResponseCode::HTTP_403;
        parent::__construct($message, $code);
    }
    
}