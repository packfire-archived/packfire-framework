<?php

class pList implements IList {
    
    /**
     *
     * @var array
     */
    protected $array = array();
    
    public function __construct($initialize){
        if($initialize instanceof self){
            $this->array = array_values($initialize->array);
        }elseif(is_array($initialize)){
            $this->array = array_values($initialize);
        }else{
            // TODO unknown variable
        }
    }

    public function count() {
        return count($this->array);
    }

    public function iterator() {
        return new pIterator($this);
    }

    public function add($item) {
        $this->array[] = $item;
        return end(array_keys($this->array));
    }

    public function clear() {
        $this->array = array();
    }

    public function contains($item) {
        return in_array($item, $this->array, true);
    }

    public function get($index) {
        $value = null;
        if($this->offsetExists($index)){
            $value = $this->array[$index];
        }else{
            // TODO throw exception
        }
        return $value;
    }

    public function indexOf($item) {
        $index = array_search($item, $this->array, true);
        if($index === false){
            $index = null;
        }
        return $index;
    }
    
    public function indexesOf($item){
        $list = new self();
        $keys = array_keys($this->array, $item, true);
        $list->array = $keys;
        return $list;
    }

    public function lastIndexOf($item) {
        $array = array_reverse($this->array, true);
        $index = array_search($item, $array, true);
        if($index === false){
            $index = null;
        }
        return $index;
    }

    public function remove($item) {
        foreach($this->array as &$value) {
            if ($value === $item){
                unset($value);
            }
        }
    }
    
    public function removeAll($list){
        foreach($this->array as &$value) {
            if (in_array($value, $list, true)){
                unset($value);
            }
        }
    }

    public function removeAt($index) {
        if($this->offsetExists($index)){
            unset($this->array[$index]);
        }else{
            // TODO: throw exception
        }
    }
    
    /**
     * Get the difference between this collection and another
     * @param pList|array $a The collection to compare against
     * @param boolean $keys (optional) If set to TRUE, the keys will be considered in the comparison
     * @return pList
     */
    public function difference($set){
        $result = new self();
        if($set instanceof self){
                $result->array = array_diff($this->array, $set->array);
        }else{
                $result->array = array_diff($this->array, $set);
        }
        return $result;
    }
    
    public function complement($set) {
        $list = new self();
        $list->array = $this->array;
        $list->removeAll($set);
        return $list;
    }

    public function intersect($set) {
        $result = new self();
        if($set instanceof self){
            $result->array = array_intersect($this->array, $set->array);
        }else{
            $result->array = array_intersect($this->array, $set);
        }
        return $result;
    }

    public function union($set) {
        $result = new self();
        $result->array = $this->array;
        $result->append($set);
        return $result;
    }

    public function append($list) {
        if($list instanceof self){
            $this->array = array_merge($this->array, $list->array);
        }elseif(is_array($list)){
            $this->array = array_merge($this->array, $list);
        }
    }

    public function prepend($list) {
        if($list instanceof self){
            $this->array = array_merge($list->array, $this->array);
        }elseif(is_array($list)){
            $this->array = array_merge($list, $this->array);
        }
    }
    
    public function getIterator() {
        return new ArrayIterator($this->array);
    }
    
    public function offsetExists($offset) {
        return $offset >= 0 && $offset < $this->count();
    }

    public function offsetGet($offset) {
        return $this->array[$offset];
    }

    public function offsetSet($offset, $value) {
        if($this->offsetExists($offset)){
            $this->array[$offset] = $value;
        }else{
            // TODO: throw exception
        }
    }

    public function offsetUnset($offset) {
        unset($this->array[$offset]);
    }
    
}