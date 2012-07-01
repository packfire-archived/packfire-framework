<?php
pload('packfire.collection.pMap');
pload('packfire.net.http.pHttpRequest');
pload('packfire.net.http.pHttpMethod');

/**
 * tMockRouteRequest Mock Class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.0-sofia
 */
class tMockRouteRequest extends pHttpRequest {
    
    private $route = '';
    
    public function __construct($uri){
        $this->route = $uri;
    }
    
    public function body($b = null) {
        return '';
    }

    public function cookies() {
        return new pMap();;
    }

    public function get() {
        return new pMap(array('data' => 200, '_urlroute_' => $this->route));
    }

    public function headers() {
        return new pMap();
    }

    public function https($h = null) {
        return false;
    }

    public function method($m = null) {
        return pHttpMethod::GET;
    }

    public function post() {
        return new pMap();
    }

    public function queryString() {
        return '';
    }

    public function time($t = null) {
        return pDateTime::now();
    }
    
    public function uri($uri = null) {
        return '/' . $this->route;
    }
    
}