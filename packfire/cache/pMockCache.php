<?php
pload('ICache');
pload('packfire.collection.pMap');

/**
 * pMockCache class
 * 
 * Provides functionality for cache mocking
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.cache
 * @since 1.0-sofia
 */
class pMockCache implements ICache {
    
    private $store;
    
    public function __construct(){
        $this->store = new pMap();
    }
    
    public function check($id) {
        return $this->store->keyExists($id);
    }

    public function clear($id) {
        $this->store->removeAt($id);
    }

    public function flush() {
        $this->store->clear();
    }

    public function garbageCollect() {
        
    }

    public function get($id, $default = null) {
        return $this->store->get($id, $default);
    }

    public function set($id, $value, $expiry) {
        $this->store->add($id, $value);
    }
    
}