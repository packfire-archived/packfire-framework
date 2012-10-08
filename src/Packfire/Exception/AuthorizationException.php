<?php
namespace Packfire\Exception;

use Exception;
use Packfire\Net\Http\HttpResponseCode;

/**
 * AuthorizationException class
 * 
 * Authorization exception message
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception
 * @since 1.0-sofia
 */
class AuthorizationException extends Exception {
    
    public function __construct($message, $code = null) {
        $this->responseCode = HttpResponseCode::HTTP_403;
        parent::__construct($message, $code);
    }
    
}