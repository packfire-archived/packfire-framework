<?php
namespace Packfire\Collection;
use Queue;

/**
 * PriorityQueue class
 * 
 * Priority Queue Implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
class PriorityQueue extends Queue {
    
    /**
     * The comparator function to perform the sorting
     * @var Closure|callback 
     * @since 1.0-sofia
     */
    private $comparator;
    
    /**
     * Create a new pPriorityQueue object
     * @param Closure|callback $comparator The comparator to perform the sort
     * @since 1.0-sofia
     */
    public function __construct($comparator) {
        parent::__construct();
        $this->comparator = $comparator;
    }
    
    /**
     * Add a item to the list.
     * @param mixed $item The item to be added.
     * @since 1.0-sofia
     */
    public function add($item) {
        parent::add($item);
        usort($this->array, $this->comparator);
    }
    
}