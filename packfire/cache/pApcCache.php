<?php
pload('ICache');

if(!function_exists('apc_fetch')){
    throw new pMissingDependencyException('pApcCache requires the APC PECL extension in order to run properly.');
}

/**
 * pApcCache class
 * 
 * Provides APC caching mechanism abstraction support
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.cache
 * @since 1.0-sofia
 */
class pApcCache implements ICache {
    
    public function check($id) {
        $result = false;
        if(function_exists('apc_exists')){
            $result = apc_exists($id);
        }else{
            apc_fetch($id, $result);
        }
        return $result;
    }

    public function clear($id) {
        apc_delete($id);
    }

    public function flush() {
        apc_clear_cache();
    }

    public function garbageCollect() {
        // does nothing because GC is handled by the server
    }

    public function get($id, $default = null) {
        $result = false;
        $value = apc_fetch($id, $result);
        if(!$result){
            $value = $default;
        }
        return $value;
    }

    public function set($id, $value, $expiry) {
        if($expiry instanceof pDateTime){
            $expiry = $expiry->toTimestamp() - time();
        }else if($expiry instanceof pTimeSpan){
            $expiry = $expiry->totalSeconds();
        }else{
            $expiry = 3600; // default to 1 hour cache?
        }
        apc_store($id, $value, $expiry);
    }
    
}