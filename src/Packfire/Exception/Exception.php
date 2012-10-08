<?php
namespace Packfire\Exception;

use Packfire\Net\Http\HttpResponseCode;

/**
 * A generic exception
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception
 * @since 1.0-sofia
 */
class pException extends Exception {
    
    protected $responseCode = HttpResponseCode::HTTP_503;
    
    public function __construct($message, $code = null) {
        if (!headers_sent()) { // takes care HTTP or not
            header('HTTP/1.1 ' . $this->responseCode);
        }
        parent::__construct($message, $code);
    }
    
}