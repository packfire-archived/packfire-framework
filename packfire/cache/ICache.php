<?php

/**
 * ICache interface
 * 
 * Abstraction for caching operations
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.cache
 * @since 1.0-sofia
 */
interface ICache {
    
    /**
     * Store the cache value uniquely identified by the identifier with expiry
     * @param string $id The identifier of the cache value
     * @param mixed $value The cache value to store
     * @param pDateTime|pTimeSpan $expiry The date time or period of time to 
     *              expire the cache value.
     * @since 1.0-sofia
     */
    public function set($id, $value, $expiry);
    
    /**
     * Check if a cache value identified by the identifier is still fresh,
     *      available and has yet to expire. 
     * @param string $id The identifier of the cache value
     * @return boolean Returns true if the cache value is fresh, available and
     *          has yet to expire. Returns false otherwise.
     * @since 1.0-sofia
     */
    public function check($id);
    
    /**
     * Retrieve the fresh cache value identified by the identifier if the
     *          cache is fresh, available and yet to expire.
     * @param string $id The identifier of the cache value
     * @param mixed $default (optional) The default value to return if the cache
     *          is stale, unavailable or expired. Defaults to null.
     * @return mixed Returns the fresh cache value or default value.
     * @since 1.0-sofia
     */
    public function get($id, $default = null);
    
    /**
     * Remove the cache value identified by the identifier
     * @param string $id The identifier of the cache value
     * @since 1.0-sofia
     */
    public function clear($id);
    
    /**
     * Perform garbage collection to remove all expired and stale cache values 
     * @since 1.0-sofia
     */
    public function garbageCollect();
    
    /**
     * Remove all cache values regardless of their state.
     * @since 1.0-sofia 
     */
    public function flush();
    
}