<?php
namespace Packfire\Linq\Query;

use Packfire\Linq\Query\Worker;

/**
 * Where class
 * 
 * A LINQ Where Query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq\Query
 * @since 1.0-sofia
 */
class Where extends Worker {
    
    /**
     * Execute the query
     * @param array $collection The collection to execute upon
     * @return array Returns the resulting array after the query execution
     * @since 1.0-sofia
     */
    public function run($collection) {
        $result = array();
        $worker = $this->worker();
        foreach($collection as $element){
            if($worker($element)){
                $result[] = $element;
            }
        }
        return $result;
    }

}