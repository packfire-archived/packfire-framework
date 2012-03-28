<?php
pload('IMap');
pload('pList');
pload('packfire.exception.pOutOfRangeException');
pload('packfire.exception.pInvalidRequestException');

/**
 * A Hash Map
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection
 * @since 1.0-sofia
 */
class pMap extends pList implements IMap {
    
    /**
     * Create a new pMap object
     * @param pMap|array $initialize (optional) If an initializing array or
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
     * @param string|pKeyValuePair $key The key name
     * @param mixed $value (optional) The item to enter. Ignored if a
     *                     pKeyValuePair is entered in the first argument.
     * @since 1.0-sofia
     */
    public function add($keyOrKVP, $value = null) {
        if($keyOrKVP instanceof pKeyValuePair){
            $this->array[$keyOrKVP->key()] = $keyOrKVP->value();
        }else{
            $this->array[$keyOrKVP] = $value;
        }
    }

    /**
     * Get the list of keys in the map
     * @return pList Returns a pList of keys.
     * @since 1.0-sofia
     */
    public function keys() {
        $list = new pList();
        $list->array = array_keys($this->array);
        return $list;
    }

    /**
     * Get the list of values from the map
     * @return pList Returns a pList of values.
     * @since 1.0-sofia
     */
    public function values() {
        $list = new pList();
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
     * Get the key of an item in the list.
     * @param mixed $item The item to look for.
     * @return string Returns the key of the item found, or null if not found.
     * @since 1.0-sofia
     */
    public function indexOf($item) {
        return parent::indexOf($item);
    }

    /**
     * Get the key of an item from the back of the list. 
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
    
    public function remove($item) {
        foreach($this->array as $key => $value) {
            if ($value === $item){
                unset($this->array[$key]);
            }
        }
    }
    
    public function removeAt($index) {
        if($this->offsetExists($index)){
            $item = $this->array[$index];
            unset($this->array[$index]);
            return $item;
        }else{
            throw new pOutOfRangeException(
                    sprintf('Unable to remove value at key %d from map.', $index)
                );
        }
    }
    
    /**
     * Get the difference between this collection and another
     * @param pList|array $a The collection to compare against
     * @return pMap
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
     * @return pMap Returns a list that is the result of the set intersect operation.
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
     * @throws pInvalidRequestException
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function offsetSet($offset, $value) {
        if($offset === null){
            throw new pInvalidRequestException(
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
