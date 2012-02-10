<?php

class pMap extends pList implements IMap {
    
    public function __construct($initialize){
        if($initialize instanceof self){
            $this->array = $initialize->array;
        }elseif(is_array($initialize)){
            $this->array = $initialize;
        }else{
            // TODO unknown variable
        }
    }
    
    public function add($key, $value) {
        $this->array[$key] = $value;
    }

    public function keys() {
        $list = new pList();
        $list->array = array_keys($this->array);
        return $list;
    }

    public function values() {
        $list = new pList();
        $list->array = array_values($this->array);
        return $list;
    }
    
    public function keyExists($key) {
        return array_key_exists($key, $this->array);
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
            $result->array = array_diff_assoc($this->array, $set->array);
        }else{
            $result->array = array_diff_assoc($this->array, $set);
        }
        return $result;
    }

    public function intersect($set) {
        $result = new self();
        if($set instanceof self){
            $result->array = array_intersect_assoc($this->array, $set->array);
        }else{
            $result->array = array_intersect_assoc($this->array, $set);
        }
        return $result;
    }
    
    public function offsetExists($offset) {
        return $this->keyExists($offset);
    }

    public function offsetSet($offset, $value) {
        if($offset === null){
            // TODO: throw exception where you need to the key
        }else{
            $this->array[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->array[$offset]);
    }
    
}
