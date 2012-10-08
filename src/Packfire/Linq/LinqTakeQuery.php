<?php
pload('ILinqQuery');

/**
 * A LINQ Take query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
class pLinqTakeQuery implements ILinqQuery {
    
    /**
     * The amount of elements to take
     * @var integer 
     * @since 1.0-sofia
     */
    private $count;
    
    /**
     * Create a new pLinqTakeQuery object
     * @param integer $count The amount of elements to take
     * @since 1.0-sofia
     */
    public function __construct($count){
        $this->count = $count;
    }
    
    /**
     * Execute the query
     * @param array $collection The collection to execute the query upon
     * @return array Returns the resulting array after the execution 
     * @since 1.0-sofia
     */
    public function run($collection) {
        return array_slice($collection, 0, $this->count);
    }
    
}