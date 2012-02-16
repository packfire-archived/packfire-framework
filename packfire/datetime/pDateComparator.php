<?php
pload('packfire.collection.sort.pPropertyComparator');

/**
 * A comparator that compares between two pDate objects
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
 * @since 1.0-sofia
 */
class pDateComparator extends pPropertyComparator {
    
    /**
     * Compare between two pDate objects
     * @param pDate $date1
     * @param pDate $date2
     * @return integer Returns 0 if they are the same, -1 if $date1 < $date2
     *                 and 1 if $date1 > $date2.
     * @since 1.0-sofia
     */
    public function compare($date1, $date2) {
        return $this->compareComponents($date1, $date2,
                array('year', 'month', 'day'));
    }
    
}