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

use Packfire\Net\Http\ResponseCode;
use \Exception as PhpException;

/**
 * A generic exception
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception
 * @since 1.0-sofia
 */
class Exception extends PhpException {
    
    protected $responseCode = ResponseCode::HTTP_503;
    
    public function __construct($message, $code = null) {
        if (!headers_sent()) { // takes care HTTP or not
            header('HTTP/1.1 ' . $this->responseCode);
        }
        parent::__construct($message, $code);
    }
    
}