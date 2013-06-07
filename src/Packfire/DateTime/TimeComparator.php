<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\DateTime;

use Packfire\Collection\Sort\Comparator\PropertyComparator;

/**
 * A comparator that compares between two Time objects
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class TimeComparator extends PropertyComparator
{
    /**
     * Compare between two Time objects
     * @param  Time    $time1 The first time to compare
     * @param  Time    $time2 The second time to compare
     * @return integer Returns 0 if they are the same, -1 if $time1 < $time2
     *                 and 1 if $time1 > $time2.
     * @since 1.0-sofia
     */
    public function compare($time1, $time2)
    {
        return $this->compareComponents(
            $time1,
            $time2,
            array('hour', 'minute', 'second', 'millisecond')
        );
    }
}
