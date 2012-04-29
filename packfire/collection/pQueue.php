<?php
pload('pList');
pload('IQueue');

/**
 * A Queue that can be enqueued from the back and dequeued from the front of the
 * queue.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection
 * @since 1.0-sofia
 */
class pQueue extends pList implements IQueue {
    
    /**
     * Enqueue an item to the back of the queue.
     * @param mixed $item The item of enqueue.
     * @since 1.0-sofia
     */
    public function enqueue($item){
        $this->add($item);
    }
    
    /**
     * Dequeue an item from the front of the queue.
     * @return mixed Returns the item that was dequeued or NULL if there is no
     *               item in the queue.
     * @since 1.0-sofia
     */
    public function dequeue(){
        $value = null;
        if($this->count() > 0){
            $value = array_shift($this->array);
        }
        return $value;
    }

    /**
     * Get the item at the front of the queue
     * @return mixed Returns the item at the front of the queue, or NULL if
     *               there is no item in the queue.
     * @since 1.0-sofia
     */
    public function front(){
        if($this->count() > 0){
            return reset($this->array);
        }
        return null;
    }
    
}
