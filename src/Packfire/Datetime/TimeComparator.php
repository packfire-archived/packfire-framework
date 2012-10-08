<?php
namespace Packfire\DateTime;

use Packfire\Collection\Sort\Comparator\PropertyComparator;

/**
 * TimeComparator class
 * 
 * A comparator that compares between two pTime objects
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class TimeComparator extends PropertyComparator {
    
    /**
     * Compare between two Time objects
     * @param Time $time1 The first time to compare
     * @param Time $time2 The second time to compare
     * @return integer Returns 0 if they are the same, -1 if $time1 < $time2
     *                 and 1 if $time1 > $time2.
     * @since 1.0-sofia
     */
    public function compare($time1, $time2) {
        return $this->compareComponents($time1, $time2,
                array('hour', 'minute', 'second', 'millisecond'));
    }
    
}