<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Yaml;

use Packfire\Collection\Map;

/**
 * Contains data of a reference map
 * You can access the data directly by $reference[$key] array access.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Yaml
 * @since 1.0-sofia
 */
class YamlReference implements \ArrayAccess
{
    /**
     * The data of the reference
     * @var array
     * @since 1.0-sofia
     */
    private $data;

    /**
     * Create a new YamlReference object
     * @param array $data The data
     * @since 1.0-sofia
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Provide direct attribute get access to the data
     * @param  string $name Name of the attribute
     * @return mixed  Returns the attribute value or NULL if not found
     * @internal
     * @since 1.0-sofia
     */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    /**
     * Provide direct attribute set access to the data
     * @param string $name  Name of the attribute
     * @param mixed  $value The value of the attribute to set
     * @internal
     * @since 1.0-sofia
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Get a Map of the data
     * @return Map Returns a Map of the data
     * @since 1.0-sofia
     */
    public function map()
    {
        return new Map($this->data);
    }

    /**
     * Array access, internal, implementation of ArrayAccess
     * @param  mixed   $offset
     * @return boolean
     * @internal
     * @since 1.0-sofia
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Array access, internal, implementation of ArrayAccess
     * @param  mixed $offset
     * @return mixed
     * @internal
     * @since 1.0-sofia
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Array access, internal, implementation of ArrayAccess
     * @param mixed $offset
     * @param mixed $value
     * @internal
     * @since 1.0-sofia
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Array access, internal, implementation of ArrayAccess
     * @param mixed $offset
     * @internal
     * @since 1.0-sofia
     */
    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, $this->data)) {
            unset($this->data[$offset]);
        }
    }
}
