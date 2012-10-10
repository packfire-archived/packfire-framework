<?php
namespace Packfire\Collection;

/**
 * IQueue interface
 * 
 * A Queue Interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
interface IQueue {
    
    public function dequeue();
    
    public function enqueue($item);
    
    public function front();
    
}