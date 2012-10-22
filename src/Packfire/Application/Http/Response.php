<?php
namespace Packfire\Application\Http;

use Packfire\Application\IAppResponse;
use Packfire\Net\Http\Response as HttpResponse;

/**
 * Response class
 * 
 * A HTTP application response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Http
 * @since 1.0-sofia
 */
class Response extends HttpResponse implements IAppResponse {
    
    public function output(){
        return $this->body();
    }
    
}