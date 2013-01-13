<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Collection\Sort\Comparator;

use Packfire\Collection\Sort\IComparator;

/**
 * A comparator that compares between two objects based on their field in common.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection\Sort\Comparator
 * @since 1.0-sofia
 */
class ObjectFieldComparator implements IComparator {
    
    /**
     * The field to compare
     * @var string
     * @since 1.0-sofia
     */
    protected $field;
    
    /**
     * Create a new ObjectFieldComparator 
     * @param string $field The field of the objects to compare
     * @since 1.0-sofia
     */
    public function __construct($field){
        $this->field = $field;
    }

    /**
     * Compare two objects based on the defined field
     * @param array|object $one The first object to compare
     * @param array|object $two The second object to compare
     * @return integer Returns -1 if $a < $b, 1 if $a > $b or 0 otherwise.
     * @since 1.0-sofia
     */
    function compare($one, $two) {
        $resultOne = $this->access($one);
        $resultTwo = $this->access($two);
        if ($resultOne == $resultTwo) {
            return 0;
        }
        return ($resultOne < $resultTwo) ? -1 : 1;
    }
    
    /**
     * Fetch the field value of an object
     * @param array|object $object
     * @return mixed Returns the field value
     * @since 1.0-sofia
     */
    protected function access($object){
        if(is_array($object)){
            return $object[$this->field];
        }else{
            return $object->{$this->field};
        }
    }

}