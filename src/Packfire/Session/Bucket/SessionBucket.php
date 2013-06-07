<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Session\Bucket;

use Packfire\Session\Bucket\ISessionBucket;

/**
 * Session Bucket default implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Session\Bucket
 * @since 1.0-sofia
 */
class SessionBucket implements ISessionBucket
{
    /**
     * The identifier of the bucket
     * @var string
     * @since 1.0-sofia
     */
    private $id;

    /**
     * The session data
     * @var array
     * @since 1.0-sofia
     */
    private $data;

    /**
     * Create a new SessionBucket object
     * @param string $id The ID of the session bucket
     * @since 1.0-sofia
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->data = array();
    }

    /**
     * Get the ID of the session bucket
     * @return string Returns the session bucket's ID
     * @since 1.0-sofia
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Clear the session bucket data
     * @since 1.0-sofia
     */
    public function clear()
    {
        $this->data = array();
    }

    /**
     * Get a specific value from the session bucket
     * @param string $name    Name of the value to retrieve
     * @param mixed  $default (optional) The default value to return if the value
     *                  is not found in the bucket
     * @return mixed Returns the value retrieved
     * @since 1.0-sofia
     */
    public function get($name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $default;
    }

    /**
     * Check if the session bucket contain a specific key
     * @param  string  $name Key to check
     * @return boolean Returns true if the bucket contains the key, false
     *              otherwise.
     * @since 1.0-sofia
     */
    public function has($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Load the bucket with data
     * @param array $data The data to be loaded
     * @since 1.0-sofia
     */
    public function load(&$data = null)
    {
        $this->data = &$data;
        if (!$this->data) {
            $this->data = array();
        }
    }

    /**
     * Remove a specific value from the session bucket
     * @param string $name Name of the value to remove
     * @since 1.0-sofia
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->data[$name]);
        }
    }

    /**
     * Set a value to the session bucket
     * @param string $name  Name of the value
     * @param mixed  $value The value
     * @since 1.0-sofia
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }
}
