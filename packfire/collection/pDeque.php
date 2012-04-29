<?php
pload('IDeque');
pload('pQueue');

/**
 * A queue that can be enqueued and dequeued from the front and back of the
 * queue.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection
 * @since 1.0-sofia
 */
class pDeque extends pQueue implements IDeque {
    
    /**
     * Enqueue the item to the front of the queue, giving the item priority.
     * @param mixed $item The item to enqueue
     * @since 1.0-sofia
     */
    public function enqueueFront($item){
        array_unshift($this->array, $item);
    }
    
    /**
     * Dequeue the item from the back of the queue.
     * @return mixed Returns the item that was dequeued from the back, or NULL
     *               if there is no item in the queue.
     * @since 1.0-sofia
     */
    public function dequeueBack(){
        $value = null;
        if($this->count() > 0){
            $value = array_pop($this->array);
        }
        return $value;
    }

    /**
     * Get the item at the back of the queue without removing it.
     * @return mixed Returns the item at the back of the queue, or NULL if
     *               there is no item in the queue.
     * @since 1.0-sofia
     */
    public function back(){
        if($this->count() > 0){
            return end($this->array);
        }
        return null;
    }
    
}