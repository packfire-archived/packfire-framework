<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Exception;

use Packfire\Exception\Exception;
use Packfire\Net\Http\ResponseCode;

/**
 * An authentication exception
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception
 * @since 1.0-sofia
 */
class AuthenticationException extends Exception {
    
    public function __construct($message, $code = null) {
        $this->responseCode = ResponseCode::HTTP_403;
        parent::__construct($message, $code);
    }
    
}