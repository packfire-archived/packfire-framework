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
 * A comparator that compares between two Date objects
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class DateComparator extends PropertyComparator
{
    /**
     * Compare between two Date objects
     * @param  Date    $date1
     * @param  Date    $date2
     * @return integer Returns 0 if they are the same, -1 if $date1 < $date2
     *                 and 1 if $date1 > $date2.
     * @since 1.0-sofia
     */
    public function compare($date1, $date2)
    {
        return $this->compareComponents($date1, $date2, array('year', 'month', 'day'));
    }
}
