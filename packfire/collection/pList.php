<?php
pload('IList');
pload('pIterator');
pload('packfire.exception.pOutOfRangeException');

/**
 * A List of Items
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection
 * @since 1.0-sofia
 */
class pList implements IList {
    
    /**
     * The internal array that stores the data
     * @var array
     * @since 1.0-sofia
     */
    protected $array = array();
    
    /**
     * Create a new pList object
     * @param pList|array $initialize (optional) If an initializing array or
     *                                list is set, the list will be populated
     *                                with the items.
     * @since 1.0-sofia
     */
    public function __construct($initialize = null){
        if(func_num_args() == 1){
            if($initialize instanceof self){
                $this->array = array_values($initialize->array);
            }elseif(is_array($initialize)){
                $this->array = array_values($initialize);
            }
        }
    }

    /**
     * The number of items in this list.
     * @return integer Returns an integer representing the number of items.
     * @since 1.0-sofia
     */
    public function count() {
        return count($this->array);
    }

    /**
     * Get the iterator for this list.
     * @return pIterator Returns a pIterator.
     * @since 1.0-sofia
     */
    public function iterator() {
        return new pIterator($this);
    }

    /**
     * Add a item to the list.
     * @param mixed $item The item to be added.
     * @return integer Returns the index of the item that was added.
     * @since 1.0-sofia
     */
    public function add($item) {
        $this->array[] = $item;
        return end(array_keys($this->array));
    }

    /**
     * Clear all the items in the list.
     * @since 1.0-sofia 
     */
    public function clear() {
        $this->array = array();
    }

    /**
     * Check if the list contains a specified item.
     * @param mixed $item The item to look for.
     * @return boolean Returns true if the list contain the item, false otherwise.
     * @since 1.0-sofia
     */
    public function contains($item) {
        return in_array($item, $this->array, true);
    }

    /**
     * Get the item at the index.
     * @param integer $index The index of the item
     * @param string $default (optional) The default value to return if the key
     *                        is not found.
     * @return mixed Returns the item or null if not found.
     * @since 1.0-sofia
     */
    public function get($index, $default = null) {
        $value = null;
        if($this->offsetExists($index)){
            $value = $this->array[$index];
        }else{
            $value = $default;
        }
        return $value;
    }

    /**
     * Get the index of an item in the list.
     * @param mixed $item The item to look for.
     * @return integer Returns the index of the item found, or null if not found.
     * @since 1.0-sofia
     */
    public function indexOf($item) {
        $index = array_search($item, $this->array, true);
        if($index === false){
            $index = null;
        }
        return $index;
    }
    
    /**
     * Get a list of the indexes of an item in the list.
     * @param mixed $item The item to look for.
     * @return pList Returns the list of indexes. 
     * @since 1.0-sofia
     */
    public function indexesOf($item){
        $list = new self();
        $keys = array_keys($this->array, $item, true);
        $list->array = $keys;
        return $list;
    }

    /**
     * Get the index of an item from the back of the list. 
     * @param mixed $item The item to look for.
     * @return integer Returns the index of the item from the back if found, or
     *                 NULL if it is not found.
     * @since 1.0-sofia
     */
    public function lastIndexOf($item) {
        $array = array_reverse($this->array, true);
        $index = array_search($item, $array, true);
        if($index === false){
            $index = null;
        }
        return $index;
    }

    /**
     * Remove an item from the array. If there are multiple counts of the same
     * item, they are all removed.
     * @param mixed $item The item to remove.
     * @since 1.0-sofia
     */
    public function remove($item) {
        foreach($this->array as $key => $value) {
            if ($value === $item){
                unset($this->array[$key]);
            }
        }
        $this->array = array_values($this->array);
    }
    
    /**
     * Remove a list of items from the list. If an item occurs multiple counts 
     * in the list, all of the instances will be removed as well.
     * @param IList|array|mixed $list,... The list of items to remove.
     * @since 1.0-sofia
     */
    public function removeAll($list){
        if(func_num_args() > 1){
            $list = func_get_args();
        }
        foreach($list as $item){
            $this->remove($item);
        }
    }

    /**
     * Remove an item by its index.
     * @param integer $index Index of the item to remove.
     * @returns mixed Returns the item removed from the list
     * @throws pOutOfRangeException
     * @since 1.0-sofia
     */
    public function removeAt($index) {
        if($this->offsetExists($index)){
            $item = $this->array[$index];
            unset($this->array[$index]);
            $this->array = array_values($this->array);
            return $item;
        }else{
            throw new pOutOfRangeException(
                    sprintf('Unable to remove value at index %d from list.', $index)
                );
        }
    }
    
    /**
     * Get an array version of the list
     * @return array Returns the array
     */
    public function toArray(){
        return $this->array;
    }
    
    /**
     * Get the difference between this collection and another
     * @param IList|array $a The collection to compare against
     * @return IList
     * @since 1.0-sofia
     */
    public function difference($set){
        $result = new self();
        if($set instanceof self){
                $result->array = array_diff($this->array, $set->array);
        }else{
                $result->array = array_diff($this->array, $set);
        }
        $result->array = array_values($result->array);
        return $result;
    }
    
    /**
     * Get the complement of this list and another ($set).
     * @param IList|array $set The list to complement
     * @return pList Returns a list that is the result of the set complement operation.
     * @since 1.0-sofia
     */
    public function complement($set) {
        $list = new self();
        $list->array = $this->array;
        $list->removeAll($set);
        return $list;
    }
    
    /**
     * Get the intersection of this list and another ($set).
     * @param IList|array $set The list to intersect
     * @return pList Returns a list that is the result of the set intersect operation.
     * @since 1.0-sofia
     */
    public function intersect($set) {
        $result = new self();
        if($set instanceof self){
            $result->array = array_intersect($this->array, $set->array);
        }else{
            $result->array = array_intersect($this->array, $set);
        }
        $result->array = array_values($result->array);
        return $result;
    }

    /**
     * Get the union of this list and another ($set).
     * @param IList|array $set The list to union with.
     * @return pList Returns a list that is the result of the set union operation.
     * @since 1.0-sofia
     */
    public function union($set) {
        $result = new self();
        $result->array = $this->array;
        $result->append($set);
        return $result;
    }

    /**
     * Append to the list with items from another list.
     * @param pList|array $list The list to append
     * @since 1.0-sofia
     */
    public function append($list) {
        if($list instanceof self){
            $this->array = array_merge($this->array, $list->array);
        }elseif(is_array($list)){
            $this->array = array_merge($this->array, $list);
        }
    }

    /**
     * Prepend to the list with items from another list.
     * @param pList|array $list The list to prepend
     * @since 1.0-sofia
     */
    public function prepend($list) {
        if($list instanceof self){
            $this->array = array_merge($list->array, $this->array);
        }elseif(is_array($list)){
            $this->array = array_merge($list, $this->array);
        }
    }
    
    /**
     * Get the first element on the list
     * @return mixed Returns the first element on the list, or NULL if there is
     *               no elements on the list.
     * @since 1.0-sofia
     */
    public function first(){
        return $this->count() === 0 ? null : reset($this->array);
    }
    
    
    /**
     * Get the last element on the list
     * @return mixed Returns the last element on the list, or NULL if there is
     *               no elements on the list.
     * @since 1.0-sofia
     */
    public function last(){
        return $this->count() === 0 ? null : end($this->array);
    }
    
    /**
     * Get the ArrayIterator for PHP foreach access
     * @return ArrayIterator 
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function getIterator() {
        return new ArrayIterator($this->array);
    }
    
    /**
     * For normal array operations
     * @return boolean 
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function offsetExists($offset) {
        return $offset >= 0 && $offset < $this->count();
    }
    
    /**
     * For normal array operations
     * @return mixed 
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function offsetGet($offset) {
        return $this->array[$offset];
    }

    /**
     * For normal array operations
     * @throws pOutOfRangeException
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function offsetSet($offset, $value) {
        if($this->offsetExists($offset) || $offset === null){
            if($offset === null){
                $offset = $this->count();
            }
            $this->array[$offset] = $value;
        }else{
            throw new pOutOfRangeException(
                    sprintf('Unable to set value at index %d into list.', $offset)
                );
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
        $this->array = array_values($this->array);
    }

}