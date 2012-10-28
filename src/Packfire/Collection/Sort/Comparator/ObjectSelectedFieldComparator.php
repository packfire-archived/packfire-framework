<?php
namespace Packfire\Collection\Sort\Comparator;

use Packfire\Collection\Sort\Comparator\ObjectFieldComparator;

/**
 * ObjectSelectedFieldComparator class
 * 
 * A comparator that compares between two objects based on their fields in common
 * defined by a field selector.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection\Sort\Comparator
 * @since 1.0-sofia
 */
class ObjectSelectedfieldComparator extends ObjectFieldComparator {
    
    /**
     * Create a new ObjectSelectedfieldComparator
     * @param callback|Closure $fieldSelector The field selecting function
     * @since 1.0-sofia
     */
    public function __construct($fieldSelector){
        $this->field = $fieldSelector;
    }
    
    /**
     * Fetch the field value of an object
     * @param array|object $object
     * @return mixed Returns the field value
     * @since 1.0-sofia
     */
    protected function access($object){
        $func = $this->field;
        if($func instanceof \Closure){
            return $func($object);
        }else{
            return call_user_func($func, $object);
        }
    }

}