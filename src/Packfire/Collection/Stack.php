<?php
namespace Packfire\Collection;

use Packfire\Collection\ArrayList;
use Packfire\Collection\IStack;

/**
 * Stack class
 * 
 * A stack of items that allows pushing and popping operations.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
class Stack extends ArrayList implements IStack {
    
    /**
     * Push an item into the top of the stack.
     * @param mixed $item The item to push.
     */
    public function push($item){
        $this->add($item);
    }
    
    /**
     * Pop an item off the top of the stack.
     * @return mixed Returns the item that was popped off, or NULL if there was
     *               no item to begin with.
     */
    public function pop(){
        $value = null;
        if($this->count() > 0){
            $value = array_pop($this->array);
        }
        return $value;
    }
    
    /**
     * Get the item at the top of the stack.
     * @return mixed Returns the item at the top of the stack, or NULL if there
     *               is no item in the stack.
     */
    public function top(){
        $value = null;
        if($this->count() > 0){
            $value = end($this->array);
        }
        return $value;
    }
    
}