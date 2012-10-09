<?php
namespace Packfire\Linq;

/**
 * ILinqQuery interface
 * 
 * A LINQ query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
interface ILinqQuery {
    
    /**
     * Execute the query
     * @param array $collection The collection to execute upon
     * @return mixed Returns the result after the query execution
     * @since 1.0-sofia
     */
    public function run($collection);
    
}