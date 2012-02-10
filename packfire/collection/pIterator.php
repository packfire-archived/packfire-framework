<?php
Packfire::load('pKeyValuePair');

/**
 * Iterator that helps to iterator through a list or array
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire/collection
 * @since 1.0-sofia
 */
class pIterator implements Countable {
    
    /**
     * The array to iterate through
     * @var IList|array
     * @since 1.0-sofia
     */
    private $array;

    /**
     * Create a pIterator
     * @param IList|array $collection The collection
     * @since 1.0-sofia
     */
    public function  __construct($collection) {
        $this->array = $collection;
    }

    /**
     * Iterate through the list and return the next key/value pair
     * @return pKeyValuePair
     * @since 1.0-sofia
     */
    public function iterate(){
        $b = each($this->array);
        if(!$b){
            return null;
        }
        return new pKeyValuePair($b['key'], $b['value']);
    }


    /**
     * Get the next element of the iteration.<br /><br />
     * <b>Warning</b>: You will not be able to distinguish between the
     *                 "end of array" false or element false when a false is
     *                 returned from this function. Use more() to check this.
     * @return mixed
     * @since 1.0-sofia
     * @see pIterator::more()
     */
    public function next(){
        return next($this->array);
    }

    /**
     * Check whether if there is more elements to iterate or not.
     * @return boolean
     * @since 1.0-sofia
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
     * Get the current element of the iteration.<br /><br />
     * <b>Warning</b>: You will not be able to distinguish between the
     *                 "end of array" false or element false when a false is
     *                 returned from this function. Use more() to check this.
     * @return mixed
     * @since 1.0-sofia
     * @see pIterator::more()
     */
    public function current(){
        return current($this->array);
    }

    /**
     * Reset the Iteration back to the first element.
     * @since 1.0-sofia
     */
    public function reset(){
        reset($this->array);
    }

    /**
     * Get the number of element in the collection
     * Note that count is cached in RaiseCollection::$count
     * Clearing cache is done after CUD operations
     * @return integer
     * @since 1.0-sofia
     */
    public function count(){
        return count($this->array);
    }
}