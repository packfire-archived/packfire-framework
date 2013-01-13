<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application\Http;

use Packfire\Application\IAppResponse;
use Packfire\Net\Http\Response as HttpResponse;

/**
 * A HTTP application response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Http
 * @since 1.0-sofia
 */
class Response extends HttpResponse implements IAppResponse {
    
    public function output(){
        return $this->body();
    }
    
}