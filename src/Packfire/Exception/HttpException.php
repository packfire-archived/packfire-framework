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

/**
 * A HTTP Exception
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception
 * @since 1.0-sofia
 */
class HttpException extends Exception
{
    /**
     * Create a new HttpException object
     * @param integer $httpCode The HTTP code
     * @param string  $message  (optional) Additional exception message
     * @since 1.0-sofia
     */
    public function __construct($httpCode, $message = null)
    {
        $http = constant('Packfire\Net\Http\ResponseCode::HTTP_' . $httpCode);
        $this->responseCode = $httpCode;
        parent::__construct($http . ($message ? ' ' . $message : ''), $httpCode);
    }
}
