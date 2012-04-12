<?php

/**
 * A Deque Abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection
 * @since 1.0-sofia
 */
interface IDeque extends IQueue {
    
    public function enqueueFront();
    
    public function back();
    
    public function dequeueBack();
    
}