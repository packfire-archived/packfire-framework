<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Session\Storage;

use Packfire\Session\Storage\ISessionStorage;
use Packfire\Session\Bucket\SessionBucket;
use Packfire\Collection\Map;

/**
 * Provides session storage
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Session\Storage
 * @since 1.0-sofia
 */
class SessionStorage implements ISessionStorage
{
    /**
     * The container of buckets
     * @var array
     * @since 1.0-sofia
     */
    private $buckets;

    /**
     * The overall storage
     * @var \Packfire\Session\SessionBucket
     * @since 1.0-sofia
     */
    private $overallBucket;

    /**
     * Create a new SessionStorage object
     * @since 1.0-sofia
     */
    public function __construct()
    {
        $this->buckets = array();
        $this->overallBucket = new SessionBucket($this->id());
        $this->registerHandler();
        $this->registerShutdown();
    }

    /**
     * Get the bucket ID for this storage
     * @return string Returns the bucket ID
     * @since 1.0-sofia
     */
    public function id()
    {
        return 'default';
    }

    /**
     * Get a value from the default session bucket
     * @param string $key     The key to identify value in the session bucket
     * @param mixed  $default (optional) The default value to return if the key
     *              is not found in the session bucket. Defaults to null.
     * @return mixed Returns the value
     * @since 1.0-sofia
     */
    public function get($key, $default = null)
    {
        return $this->overallBucket->get($key, $default);
    }

    /**
     * Remove a value from the default session bucket
     * @param string $key The key to remove
     * @since 1.0-sofia
     */
    public function remove($key)
    {
        $this->overallBucket->remove($key);
    }

    /**
     * Set a value to the default session bucket
     * @param string $key  The key to uniquely identify the value
     * @param mixed  $data The value to set to the bucket
     * @since 1.0-sofia
     */
    public function set($key, $data)
    {
        $this->overallBucket->set($key, $data);
    }

    /**
     * Regenerate a new session ID
     * @param boolean $delete (optional) Set to delete old session or not
     * @since 1.0-sofia
     */
    public function regenerate($delete = false)
    {
        session_regenerate_id($delete);
    }

    /**
     * Register the session handler
     * @since 1.0-sofia
     */
    protected function registerHandler()
    {
        if ($this instanceof ISessionHandler
                || $this instanceof \SessionHandlerInterface) {
            session_set_save_handler(
                array($this, 'open'),
                array($this, 'close'),
                array($this, 'read'),
                array($this, 'write'),
                array($this, 'destroy'),
                array($this, 'gc')
            );
        }
    }

    /**
     * Register the write close shutdown function
     * @since 1.0-sofia
     */
    protected function registerShutdown()
    {
        register_shutdown_function('session_write_close');
    }

    /**
     * Register a bucket to the storage
     * @param ISessionBucket $bucket The bucket to register
     * @since 1.0-sofia
     */
    public function register($bucket)
    {
        $id = $bucket->id();
        if ($id) {
            $this->buckets[$id] = $bucket;
            $bucket->load($_SESSION[$id]);
        }
    }

    public function bucket($id)
    {
        if (isset($this->buckets[$id])) {
            return $this->buckets->get($id);
        }
        return null;
    }

    public function clear()
    {
        $this->overallBucket->clear();
    }

    public function load(&$data = null)
    {
        if (func_num_args() == 0) {
            $data = &$_SESSION;
        }

        $this->overallBucket->load($data);

        foreach ($this->buckets as $id => $bucket) {
            if (!isset($data[$id])) {
                $data[$id] = array();
            }
            $bucket->load($data[$id]);
        }

    }

    public function has($key)
    {
        $this->overallBucket->has($key);
    }
}
