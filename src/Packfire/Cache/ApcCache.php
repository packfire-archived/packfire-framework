<?php
namespace Packfire\Cache;

use ICache;
use Packfire\Exception\MissingDependencyException;

if(!function_exists('apc_fetch')){
    throw new MissingDependencyException('pApcCache requires the APC PECL extension in order to run properly.');
}

/**
 * ApcCache class
 * 
 * Provides APC caching mechanism abstraction support
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Cache
 * @since 1.0-sofia
 */
class ApcCache implements ICache {
    
    /**
     * Check if a cache value identified by the identifier is still fresh,
     *      available and has yet to expire. 
     * @param string $cacheId The identifier of the cache value
     * @return boolean Returns true if the cache value is fresh, available and
     *          has yet to expire. Returns false otherwise.
     * @since 1.0-sofia
     */
    public function check($cacheId) {
        $result = false;
        if(function_exists('apc_exists')){
            $result = apc_exists($cacheId);
        }else{
            apc_fetch($cacheId, $result);
        }
        return $result;
    }

    /**
     * Remove the cache value identified by the identifier
     * @param string $cacheId The identifier of the cache value
     * @since 1.0-sofia
     */
    public function clear($cacheId) {
        apc_delete($cacheId);
    }

    /**
     * Remove all cache values regardless of their state.
     * @since 1.0-sofia 
     */
    public function flush() {
        apc_clear_cache();
    }

    /**
     * Perform garbage collection to remove all expired and stale cache values 
     * @since 1.0-sofia
     */
    public function garbageCollect() {
        // does nothing because GC is handled by the server
    }

    /**
     * Retrieve the fresh cache value identified by the identifier if the
     *          cache is fresh, available and yet to expire.
     * @param string $cacheId The identifier of the cache value
     * @param mixed $default (optional) The default value to return if the cache
     *          is stale, unavailable or expired. Defaults to null.
     * @return mixed Returns the fresh cache value or default value.
     * @since 1.0-sofia
     */
    public function get($cacheId, $default = null) {
        $result = false;
        $value = apc_fetch($cacheId, $result);
        if(!$result){
            $value = $default;
        }
        return $value;
    }

    /**
     * Store the cache value uniquely identified by the identifier with expiry
     * @param string $cacheId The identifier of the cache value
     * @param mixed $value The cache value to store
     * @param pDateTime|pTimeSpan $expiry The date time or period of time to 
     *              expire the cache value.
     * @since 1.0-sofia
     */
    public function set($cacheId, $value, $expiry) {
        if($expiry instanceof pDateTime){
            $expiry = $expiry->toTimestamp() - time();
        }else if($expiry instanceof pTimeSpan){
            $expiry = $expiry->totalSeconds();
        }else{
            $expiry = 3600; // default to 1 hour cache?
        }
        apc_store($cacheId, $value, $expiry);
    }
    
}