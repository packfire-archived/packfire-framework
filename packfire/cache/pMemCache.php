<?php
pload('ICache');

/**
 * pMemCache class
 * 
 * Memcached caching functionality
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.cache
 * @since 1.0-sofia
 */
class pMemCache implements ICache {
    
    /**
     * The Memcache instance
     * @var Memcache
     * @since 1.0-sofia
     */
    private $memcache;
    
    public function __construct($host, $port = null, $timeout = null) {
        $this->memcache = new Memcache();
        $this->memcache->connect($host, $port, $timeout);
    }
    
    public function check($id) {
        return $this->memcache->get($id) !== false;
    }

    public function clear($id) {
        $this->memcache->delete($id);
    }

    public function flush() {
        $this->memcache->flush();
    }

    public function garbageCollect() {
        // does nothing because GC is handled by the server
    }

    public function get($id, $default = null) {
        $data = $this->memcache->get($id);
        if($data === false){
            $data = $default;
        }
        return $data;
    }

    public function set($id, $value, $expiry) {
        if($expiry instanceof pDateTime){
            $expiry = $expiry->toTimestamp();
        }else if($expiry instanceof pTimeSpan){
            $expiry = time() + $expiry->totalSeconds();
        }else{
            $expiry = time() + 3600; // default to 1 hour cache?
        }
        $this->memcache->set($id, $value, 0, $expiry);
    }
    
}