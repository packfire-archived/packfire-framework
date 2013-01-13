<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Net\Http;

/**
 * HTTP Response Code constants
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Net\Http
 * @since 1.0-sofia
 */
class ResponseCode {

    const HTTP_100 = '100 Continue';
    const HTTP_101 = '101 Switching Protocols';
    const HTTP_102 = '102 Processing';

    const HTTP_200 = '200 OK';
    const HTTP_201 = '201 Created';
    const HTTP_202 = '202 Accepted';
    const HTTP_203 = '203 Non-Authoritative Information';
    const HTTP_204 = '204 No Content';
    const HTTP_205 = '205 Reset Content';
    const HTTP_206 = '206 Partial Content';
    const HTTP_207 = '207 Multi-Status';

    const HTTP_300 = '300 Multiple Choices';
    const HTTP_301 = '301 Moved Permanently';
    const HTTP_302 = '302 Found';
    const HTTP_303 = '303 See Other';
    const HTTP_304 = '304 Not Modified';
    const HTTP_305 = '305 Use Proxy';
    const HTTP_307 = '307 Temporary Redirect';

    const HTTP_400 = '400 Bad Request';
    const HTTP_401 = '401 Unauthorized';
    const HTTP_402 = '402 Payment Required';
    const HTTP_403 = '403 Forbidden';
    const HTTP_404 = '404 Not Found';
    const HTTP_405 = '405 Method Not Allowed';
    const HTTP_406 = '406 Not Acceptable';
    const HTTP_407 = '407 Proxy Authentication Required';
    const HTTP_408 = '408 Request Time-out';
    const HTTP_409 = '409 Conflict';
    const HTTP_410 = '410 Gone';
    const HTTP_411 = '411 Length Required';
    const HTTP_412 = '412 Precondition Failed';
    const HTTP_413 = '413 Request Entity Too Large';
    const HTTP_414 = '414 Request-URI Too Large';
    const HTTP_415 = '415 Unsupported Media Type';
    const HTTP_416 = '416 Requested Range Not Satisfiable';
    const HTTP_417 = '417 Expectation Failed';
    const HTTP_418 = '418 I\'m a teapot'; // april fools :D
    const HTTP_422 = '422 Unprocessable Entity';
    const HTTP_423 = '423 Locked';
    const HTTP_424 = '424 Failed Dependency';
    const HTTP_425 = '425 Unordered Collection';
    const HTTP_426 = '426 Upgrade Required';
    const HTTP_444 = '444 No Response';
    const HTTP_449 = '449 Retry With';
    const HTTP_450 = '423 Blocked By Windows Parental Controls';
    const HTTP_499 = '499 Client Closed Request';

    const HTTP_500 = '500 Internal Server Error';
    const HTTP_501 = '501 Not Implemented';
    const HTTP_502 = '502 Bad Gateway';
    const HTTP_503 = '503 Service Unavailable';
    const HTTP_504 = '504 Gateway Time-out';
    const HTTP_505 = '505 HTTP Version Not Supported';
    const HTTP_506 = '506 Variant Also Negotiates';
    const HTTP_507 = '507 Insufficient Storage';
    const HTTP_509 = '509 Bandwidth Limit Exceeded';
    const HTTP_510 = '510 Not Extended';
    
}