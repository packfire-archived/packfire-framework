<?php
pload('ILinqQuery');

/**
 * A LINQ Skip query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
class pLinqSkipQuery implements ILinqQuery {
    
    /**
     * The amount of elements to skip
     * @var integer
     * @since 1.0-sofia
     */
    private $count;
    
    /**
     * Create a new pLinqSkipQuery object
     * @param integer $count The amount of elements to skip
     * @since 1.0-sofia
     */
    public function __construct($count){
        $this->count = $count;
    }
    
    /**
     * Execute the query
     * @param array $collection The collection to execute upon
     * @return array Returns the resulting array after the query execution
     * @since 1.0-sofia
     */
    public function run($collection) {
        return array_slice($collection, $this->count);
    }
    
}