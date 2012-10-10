<?php
namespace Packfire\Test\Mocks;

use Packfire\Collection\Map;
use Packfire\Application\Http\Request;
use Packfire\Net\Http\Method as HttpMethod;
use Packfire\DateTime\DateTime;

/**
 * RouteRequest Mock Class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Test\Mocks
 * @since 1.0-sofia
 */
class RouteRequest extends Request {
    
    private $route = '';
    
    public function __construct($uri, $server){
        parent::__construct(null, $server);
        parent::method(HttpMethod::GET);
        $this->route = $uri;
    }
    
    public function body($b = null) {
        return '';
    }

    public function cookies() {
        return new Map();;
    }

    public function get() {
        return new Map(array());
    }

    public function https($h = null) {
        return false;
    }

    public function post() {
        return new Map();
    }

    public function queryString() {
        return '';
    }

    public function time($t = null) {
        return DateTime::now();
    }
    
    public function uri($uri = null) {
        return '/' . $this->route;
    }
    
}