<?php
namespace Packfire\Linq\Query;

use Packfire\Linq\Query\Worker;
use Packfire\Collection\ArrayList;
use Packfire\Collection\Map;
use Packfire\Collection\KeyValuePair;

/**
 * GroupBy class
 * 
 * A Group By LINQ query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq\Query
 * @since 1.0-sofia
 */
class GroupBy extends Worker {
    
    /**
     * Execute the query
     * @param array $collection The collection to execute upon
     * @return mixed Returns the result after the query execution
     * @since 1.0-sofia
     */
    public function run($collection) {
        $grouping = array();
        $worker = $this->worker();
        $matrix = new Map();
        
        foreach($collection as $element){
            $key = $worker($element);
            if(!$matrix->keyExists($key)){
                $matrix->add($key, new ArrayList());
            }
            $matrix->get($key)->add($element);
        }
        
        foreach($matrix as $key => $value){
            $grouping[] = new KeyValuePair($key, $value);
        }
        return $grouping;
    }
    
}