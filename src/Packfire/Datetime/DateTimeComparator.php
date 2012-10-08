<?php
namespace Packfire\DateTime;

use Packfire\Collection\Sort\IComparator;
use DateComparator;
use TimeComparator;

/**
 * DateTimeComparator class
 * 
 * A date time comparator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
 * @since 1.0-sofia
 */
class DateTimeComparator implements IComparator {
    
    /**
     * Compares between two DateTime object
     * @param DateTime $datetime1 The first pDateTime object to compare
     * @param DateTime $datetime2 The second pDateTime object to compare
     * @return integer Returns 0 if they are the same, -1 if $datetime1 < $datetime2
     *                 and 1 if $datetime1 > $datetime2.
     * @since 1.0-sofia
     */
    public function compare($datetime1, $datetime2) {
        $dateComp = new DateComparator();
        $result = $dateComp->compare($datetime1, $datetime2);
        if($result === 0){
            $timeComp = new TimeComparator();
            $result = $timeComp->compare($datetime1->time(), $datetime2->time());
        }
        return $result;
    }
    
}