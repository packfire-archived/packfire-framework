<?php

class pIterator implements Countable {
    
    /**
     * The array to iterate through
     * @var array
     */
    private $array;

    /**
     * Create a RaiseIterator for a RaiseCollection
     * @param IList $collection
     */
    public function  __construct($collection) {
        $this->array = $collection;
    }

    /**
     * Iterate through the Collection and return the next key/value pair
     * @return RaiseKeyValuePair
     * @link http://php.net/each
     */
    public function iterate(){
        $b = each($this->array);
        if(!$b){
            return null;
        }
        return new pKeyValuePair($b['key'], $b['value']);
    }


    /**
     * Get the next element of the iteration
     * Note that you will not be able to distinguish between the "end of array" false or element false when a false is returned from this function
     * @return mixed
     */
    public function next(){
        return next($this->array);
    }

    /**
     * Check whether if there is more elements to iterate or not
     * @return boolean
     */
    public function more(){
        $ret = each($this->array);
        if($ret === false){
            return false;
        }else{
            prev($this->array);
            return true;
        }
    }

    /**
     * Get the current element of the iteration
     * Note that you will not be able to distinguish between the "end of array" false or element false when a false is returned from this function
     * @return mixed
     */
    public function current(){
        return current($this->array);
    }

    /**
     * Reset the Iteration back to the first element
     * @link http://php.net/reset
     */
    public function reset(){
        reset($this->array);
    }

    /**
     * Get the number of element in the collection
     * Note that count is cached in RaiseCollection::$count
     * Clearing cache is done after CUD operations
     * @return integer
     * @link http://php.net/count
     */
    public function count(){
        return count($this->array);
    }
}