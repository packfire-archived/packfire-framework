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
use Packfire\Collection\Sort\Comparator\ObjectSelectedFieldComparator;

/**
 * Compares two objects based on their properties
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection\Sort\Comparator
 * @since 1.0-sofia
 */
abstract class PropertyComparator implements IComparator
{
    /**
     * Compares two objects based on a particular component
     * @param  object  $value1 First value to compare
     * @param  object  $value2 Second value to compare
     * @param  string  $com    Component Name
     * @return integer Return 0 if both values are equal, 1 if $value2 > $value1
     *                 and -1 if $value2 < $value1
     * @since 1.0-sofia
     */
    private function compareComponent($value1, $value2, $com)
    {
        $comparator = new ObjectSelectedFieldComparator(
                function($object) use ($com) {
                    return $object->$com();
                });

        return $comparator->compare($value2, $value1);
    }

    /**
     * Compare between two objects based on their components
     * @param  object          $o1         The first object to compare
     * @param  object          $o2         The second object to compare
     * @param  array|ArrayList $components The components to compare
     * @return integer         Returns 0 if they are the same, -1 if $o1 < $o2 and 1 if
     *                 $o1 > $o2.
     * @since 1.0-sofia
     */
    protected function compareComponents($o1, $o2, $components)
    {
        $componentComp = 0;
        foreach ($components as $comp) {
            $componentComp = $this->compareComponent($o1, $o2, $comp);
            if ($componentComp != 0) {
                break;
            }
        }

        return $componentComp;
    }

}
