<?php

class pList implements IList {
    
    /**
     *
     * @var array
     */
    private $array = array();
    
    public function offsetExists($offset) {
        
    }

    public function offsetGet($offset) {
        
    }

    public function offsetSet($offset, $value) {
        
    }

    public function offsetUnset($offset) {
        
    }

    public function count() {
        
    }

    public function iterator() {
        
    }

    public function add($item) {
        
    }

    public function clear() {
        
    }

    public function contains($item) {
        
    }

    public function get($index) {
        $value = null;
        if($index >= 0 && $index < $this->count()){
            return $this->array[$index];
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
        $list = new pList();
        foreach($this->array as $a){
            if($a === $item){
                $list->add($a);
            }
        }
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
        
    }

    public function removeAt($index) {
        
    }

    public function getIterator() {
        return new ArrayIterator($this->array);
    }
    
}