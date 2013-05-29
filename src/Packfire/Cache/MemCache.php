<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Cache;

use Packfire\Cache\ICache;
use Packfire\Exception\MissingDependencyException;
use Packfire\DateTime\DateTime;
use Packfire\DateTime\TimeSpan;

if (!class_exists('\Memcache')) {
    throw new MissingDependencyException('MemCache requires the Memcache extension in order to run properly.');
}

/**
 * Memcached caching functionality
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Cache
 * @since 1.0-sofia
 */
class MemCache implements ICache
{

    /**
     * The Memcache instance
     * @var \Memcache
     * @since 1.0-sofia
     */
    private $memcache;

    /**
     * Create a new MemCache object
     * @param string  $host    The host that is hosting the memcache server
     * @param integer $port    (optional) The port to connect to
     * @param integer $timeout (optional) Connection time out
     * @since 1.0-sofia
     */
    public function __construct($host, $port = null, $timeout = null)
    {
        $this->memcache = new \Memcache();
        $this->memcache->connect($host, $port, $timeout);
    }

    /**
     * Check if a cache value identified by the identifier is still fresh,
     *      available and has yet to expire.
     * @param  string  $cacheId The identifier of the cache value
     * @return boolean Returns true if the cache value is fresh, available and
     *          has yet to expire. Returns false otherwise.
     * @since 1.0-sofia
     */
    public function check($cacheId)
    {
        return $this->memcache->get($cacheId) !== false;
    }

    /**
     * Remove the cache value identified by the identifier
     * @param string $cacheId The identifier of the cache value
     * @since 1.0-sofia
     */
    public function clear($cacheId)
    {
        $this->memcache->delete($cacheId);
    }

    /**
     * Remove all cache values regardless of their state.
     * @since 1.0-sofia
     */
    public function flush()
    {
        $this->memcache->flush();
    }

    /**
     * Perform garbage collection to remove all expired and stale cache values
     * @since 1.0-sofia
     */
    public function garbageCollect()
    {
        // does nothing because GC is handled by the server
    }

    /**
     * Retrieve the fresh cache value identified by the identifier if the
     *          cache is fresh, available and yet to expire.
     * @param string $cacheId The identifier of the cache value
     * @param mixed  $default (optional) The default value to return if the cache
     *          is stale, unavailable or expired. Defaults to null.
     * @return mixed Returns the fresh cache value or default value.
     * @since 1.0-sofia
     */
    public function get($cacheId, $default = null)
    {
        $data = $this->memcache->get($cacheId);
        if ($data === false) {
            $data = $default;
        }

        return $data;
    }

    /**
     * Store the cache value uniquely identified by the identifier with expiry
     * @param string            $cacheId The identifier of the cache value
     * @param mixed             $value   The cache value to store
     * @param DateTime|TimeSpan $expiry  (optional) The date time or period of
     *          time to expire the cache value. If not set, the item will
     *          never expire.
     * @since 1.0-sofia
     */
    public function set($cacheId, $value, $expiry = null)
    {
        if (func_num_args() == 3) {
            if ($expiry instanceof DateTime) {
                $expiry = $expiry->toTimestamp() - time();
            } elseif ($expiry instanceof TimeSpan) {
                $expiry = $expiry->totalSeconds();
            } else {
                $expiry = 3600; // default to 1 hour cache?
            }
            $this->memcache->set($cacheId, $value, 0, $expiry);
        } else {
            $this->memcache->set($cacheId, $value, 0);
        }
    }
}
