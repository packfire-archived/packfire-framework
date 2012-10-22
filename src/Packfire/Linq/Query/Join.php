<?php
namespace Packfire\Linq\Query;

use Packfire\Linq\IQuery;
use Packfire\Collection\Map;

/**
 * Join class
 * 
 * A LINQ Join query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq\Query
 * @since 1.0-sofia
 */
class Join implements IQuery {
    
    private $joinCollection;
    private $innerKeySelector;
    private $outerKeySelector;
    private $resultSelector;
    
    /**
     * Create a new LinqJoinQuery object
     * @param mixed $joinCollection
     * @param mixed $innerKey
     * @param mixed $outerKey
     * @param mixed $resultSelector
     * @since 1.0-sofia
     */
    public function __construct($joinCollection, $innerKey, $outerKey, $resultSelector){
        $this->joinCollection = $joinCollection;
        $this->innerKeySelector = $innerKey;
        $this->outerKeySelector = $outerKey;
        $this->resultSelector = $resultSelector;
    }
    
    /**
     * Execute the query
     * @param array $collection The collection to execute upon
     * @return mixed Returns the result after the query execution
     * @since 1.0-sofia
     */
    public function run($collection) {
        $result = array();
        
        $outerKeyCache = new Map();
        foreach($this->joinCollection as $k => $joinElement){
            $outerKey = call_user_func($this->outerKeySelector, $joinElement);
            $outerKeyCache->add($k, $outerKey);
        }
        
        foreach($collection as $element){
            $innerKey = call_user_func($this->innerKeySelector, $element);
            foreach($this->joinCollection as $k => $joinElement){
                if($innerKey === $outerKeyCache[$k]){
                    $result[] = call_user_func($this->resultSelector, $element, $joinElement);
                }
            }
        }
        
        return $result;
    }
    
}