<?php
pload('ILinqQuery');

/**
 * A LINQ Join query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.linq
 * @since 1.0-sofia
 */
class pLinqJoinQuery implements ILinqQuery {
    
    private $joinCollection;
    private $innerKeySelector;
    private $outerKeySelector;
    private $resultSelector;
    
    public function __construct($joinCollection, $innerKey, $outerKey, $resultSelector){
        $this->joinCollection = $joinCollection;
        $this->innerKeySelector = $innerKey;
        $this->outerKeySelector = $outerKey;
        $this->resultSelector = $resultSelector;
    }
    
    public function run($collection) {
        $result = array();
        
        $outerKeyCache = new pMap();
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