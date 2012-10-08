<?php
namespace Packfire\Collection;

use IMap;
use ArrayList;
use KeyValuePair;
use Packfire\Exception\InvalidRequestException;
use Packfire\Exception\OutOfRangeException;

/**
 * Map class
 * 
 * A Hash Map
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
class Map extends ArrayList implements IMap {
    
    /**
     * Create a new Map object
     * @param Map|array $initialize (optional) If an initializing array or
     *                                list is set, the list will be populated
     *                                with the items.
     * @since 1.0-sofia
     */
    public function __construct($initialize = null){
        if(func_num_args() == 1){
            if($initialize instanceof self){
                $this->array = $initialize->array;
            }elseif(is_array($initialize)){
                $this->array = $initialize;
            }
        }
    }
    
    /**
     * Add a new item to the map
     * @param string|pKeyValuePair $keyOrKVP The key name
     * @param mixed $value (optional) The item to enter. Ignored if a
     *                     pKeyValuePair is entered in the first argument.
     * @since 1.0-sofia
     */
    public function add($keyOrKVP, $value = null) {
        if($keyOrKVP instanceof KeyValuePair){
            $this->array[$keyOrKVP->key()] = $keyOrKVP->value();
        }else{
            $this->array[$keyOrKVP] = $value;
        }
    }

    /**
     * Get the list of keys in the map
     * @return ArrayList Returns a list of keys.
     * @since 1.0-sofia
     */
    public function keys() {
        $list = new ArrayList();
        $list->array = array_keys($this->array);
        return $list;
    }

    /**
     * Get the list of values from the map
     * @return ArrayList Returns a list of values.
     * @since 1.0-sofia
     */
    public function values() {
        $list = new ArrayList();
        $list->array = array_values($this->array);
        return $list;
    }
    
    /**
     * Get the item using its key.
     * @param string $key The key of the item.
     * @param string $default (optional) The default value to return if the key
     *                        is not found.
     * @return mixed Returns the item or null if not found.
     */
    public function get($key, $default = null) {
        return parent::get($key, $default);
    }

    /**
     * Get the index of an item occurring first in the map.
     * @param mixed $item The item to look for.
     * @return string Returns the key of the item found, or null if not found.
     * @since 1.0-sofia
     */
    public function indexOf($item) {
        return parent::indexOf($item);
    }

    /**
     * Get the key of an item from the back of the map. 
     * @param mixed $item The item to look for.
     * @return string Returns the key of the item from the back if found, or
     *                 NULL if it is not found.
     * @since 1.0-sofia
     */
    public function lastIndexOf($item) {
        return parent::lastIndexOf($item);
    }
    
    /**
     * Check if a key exists in the hash map.
     * @param string $key The key to check for existence.
     * @return boolean Returns true if the key exists, false otherwise.
     * @since 1.0-sofia
     */
    public function keyExists($key) {
        return array_key_exists($key, $this->array);
    }
    
    /**
     * Remove an item from the array. If there are multiple counts of the same
     * item, they are all removed.
     * @param mixed $item The item to remove.
     * @return integer Returns the number of items removed from the list
     * @since 1.0-sofia
     */
    public function remove($item) {
        $keys = array_keys($this->array, $item, true);
        foreach($keys as $key){
            unset($this->array[$key]);
        }
        return count($keys);
    }
    
    /**
     * Remove an item by its key.
     * @param integer|string $index Index of the item to remove.
     * @returns mixed Returns the item removed from the list
     * @throws pOutOfRangeException
     * @since 1.0-sofia
     */
    public function removeAt($index) {
        if($this->offsetExists($index)){
            $item = $this->array[$index];
            unset($this->array[$index]);
            return $item;
        }else{
            throw new OutOfRangeException(
                    sprintf('Unable to remove value at key %s from map.', $index)
                );
        }
    }
    
    /**
     * Get the difference between this collection and another
     * @param ArrayList|array $set The collection to compare against
     * @return Map Returns the difference
     * @since 1.0-sofia
     */
    public function difference($set){
        $result = new self();
        if($set instanceof self){
            $result->array = array_diff_assoc($this->array, $set->array);
        }else{
            $result->array = array_diff_assoc($this->array, $set);
        }
        return $result;
    }

    /**
     * Get the intersection of this list and another ($set).
     * @param IList|array $set The list to intersect
     * @return Map Returns a list that is the result of the set intersect operation.
     * @since 1.0-sofia
     */
    public function intersect($set) {
        $result = new self();
        if($set instanceof self){
            $result->array = array_intersect_assoc($this->array, $set->array);
        }else{
            $result->array = array_intersect_assoc($this->array, $set);
        }
        return $result;
    }
    
    /**
     * For normal array operations
     * @return boolean 
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function offsetExists($offset) {
        return $this->keyExists($offset);
    }

    /**
     * For normal array operations
     * @throws InvalidRequestException
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function offsetSet($offset, $value) {
        if($offset === null){
            throw new InvalidRequestException(
                    'Unable to set value without key into a map.'
                );
        }else{
            $this->array[$offset] = $value;
        }
    }

    /**
     * For normal array operations
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function offsetUnset($offset) {
        unset($this->array[$offset]);
    }
    
}
