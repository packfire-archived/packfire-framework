<?php
pload('ICache');

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
    
    public function check($id) {
        return false;
    }

    public function clear($id) {
        
    }

    public function flush() {
        
    }

    public function garbageCollect() {
        
    }

    public function get($id, $default = null) {
        return $default;
    }

    public function set($id, $value, $expiry) {
        
    }
    
}