<?php
pload('pQueue');

/**
 * Priority Queue Implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection
 * @since 1.0-sofia
 */
class pPriorityQueue extends pQueue {
    
    private $comparator;
    
    public function add($item) {
        reset($this->array);
        $offset = 0;
        while($element = next($this->array)){
            // ($item < $element) === -1
            $res = call_user_func($this->comparator, $item, $element); 
            if($res == -1){
                $this->array = array_merge(
                        array_splice($this->array, 0, $offset),
                        array($item),
                        array_splice($this->array, $offset)
                    );
                break;
            }
            ++$offset;
        }
    }
    
    public function comparator($comparator = null){
        if(func_num_args() == 1){
            $this->comparator = $comparator;
        }
        return $this->comparator;
    }
    
}