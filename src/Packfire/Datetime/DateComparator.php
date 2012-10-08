<?php
namespace Packfire\DateTime;

use Packfire\Collection\Sort\Comparator\PropertyComparator;

/**
 * DateComparator class
 * 
 * A comparator that compares between two pDate objects
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class DateComparator extends PropertyComparator {
    
    /**
     * Compare between two pDate objects
     * @param Date $date1
     * @param Date $date2
     * @return integer Returns 0 if they are the same, -1 if $date1 < $date2
     *                 and 1 if $date1 > $date2.
     * @since 1.0-sofia
     */
    public function compare($date1, $date2) {
        return $this->compareComponents($date1, $date2,
                array('year', 'month', 'day'));
    }
    
}