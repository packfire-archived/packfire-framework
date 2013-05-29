<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Collection;

use Packfire\Collection\KeyValuePair;

/**
 * Iterator that helps to iterator through a list or array
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
class Iterator implements \Countable
{
    /**
     * The array to iterate through
     * @var IList|array
     * @since 1.0-sofia
     */
    private $array;

    /**
     * Create a new Iterator object
     * @param IList|array $collection The collection
     * @since 1.0-sofia
     */
    public function  __construct($collection)
    {
        $this->array = $collection;
    }

    /**
     * Iterate through the list and return the next key/value pair
     * @return KeyValuePair Returns the next key value pair
     * @since 1.0-sofia
     */
    public function iterate()
    {
        $item = each($this->array);
        if (!$item) {
            return null;
        }

        return new KeyValuePair($item['key'], $item['value']);
    }

    /**
     * Get the next element of the iteration.<br /><br />
     * <b>Warning</b>: You will not be able to distinguish between the
     *                 "end of array" false or element false when a false is
     *                 returned from this function. Use more() to check this.
     * @return mixed
     * @since 1.0-sofia
     * @see Iterator::more()
     */
    public function next()
    {
        return next($this->array);
    }

    /**
     * Check whether if there is more elements to iterate or not.
     * @return boolean
     * @since 1.0-sofia
     */
    public function more()
    {
        $ret = each($this->array);
        if ($ret === false) {
            return false;
        } else {
            prev($this->array);

            return true;
        }
    }

    /**
     * Get the current element of the iteration.<br /><br />
     * <b>Warning</b>: You will not be able to distinguish between the
     *                 "end of array" false or element false when a false is
     *                 returned from this function. Use more() to check this.
     * @return mixed
     * @since 1.0-sofia
     * @see Iterator::more()
     */
    public function current()
    {
        return current($this->array);
    }

    /**
     * Reset the Iteration back to the first element.
     * @since 1.0-sofia
     */
    public function reset()
    {
        reset($this->array);
    }

    /**
     * Get the number of element in the collection
     * Clearing cache is done after CUD operations
     * @return integer
     * @since 1.0-sofia
     */
    public function count()
    {
        return count($this->array);
    }

}
