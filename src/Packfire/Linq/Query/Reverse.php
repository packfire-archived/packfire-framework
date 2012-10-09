<?php
namespace Packfire\Linq\Query;

use Packfire\Linq\IQuery;

/**
 * Reverse class
 * 
 * A LINQ reverse query that reverses the collection
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq\Query
 * @since 1.0-sofia
 */
class Reverse implements IQuery {
    
    /**
     * Execute the query
     * @param array $collection The collection to execute upon
     * @return array Returns the resulting array after the query execution
     * @since 1.0-sofia
     */
    public function run($collection) {
        return array_reverse($collection);
    }

}