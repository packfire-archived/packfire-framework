<?php
namespace Packfire\Linq;

use ILinqQuery;

/**
 * LinqSkipQuery class
 * 
 * A LINQ Skip query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq
 * @since 1.0-sofia
 */
class LinqSkipQuery implements ILinqQuery {
    
    /**
     * The amount of elements to skip
     * @var integer
     * @since 1.0-sofia
     */
    private $count;
    
    /**
     * Create a new LinqSkipQuery object
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