<?php
pload('packfire.collection.sort.pPropertyComparator');

/**
 * A comparator that compares between two pTime objects
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
 * @since 1.0-sofia
 */
class pTimeComparator extends pPropertyComparator {
    
    /**
     * Compare between two pTime objects
     * @param pTime $time1
     * @param pTime $time2
     * @return integer Returns 0 if they are the same, -1 if $time1 < $time2
     *                 and 1 if $time1 > $time2.
     * @since 1.0-sofia
     */
    public function compare($time1, $time2) {
        return $this->compareComponents($time1, $time2,
                array('hour', 'minute', 'second', 'millisecond'));
    }
    
}