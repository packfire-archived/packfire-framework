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

use Packfire\Collection\Sort\Comparator\ObjectFieldComparator;

/**
 * A comparator that compares between two objects based on their fields in common
 * defined by a field selector.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection\Sort\Comparator
 * @since 1.0-sofia
 */
class ObjectSelectedFieldComparator extends ObjectFieldComparator
{
    /**
     * Create a new ObjectSelectedfieldComparator
     * @param callback|Closure $fieldSelector The field selecting function
     * @since 1.0-sofia
     */
    public function __construct($fieldSelector)
    {
        $this->field = $fieldSelector;
    }

    /**
     * Fetch the field value of an object
     * @param  array|object $object
     * @return mixed        Returns the field value
     * @since 1.0-sofia
     */
    protected function access($object)
    {
        $func = $this->field;
        if ($func instanceof \Closure) {
            return $func($object);
        } else {
            return call_user_func($func, $object);
        }
    }
}
