<?php
pload('packfire.collection.sort.IComparator');

/**
 * pDateTimeComparator Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
 * @since 1.0-sofia
 */
class pDateTimeComparator implements IComparator {
    
    /**
     * Compares between two pDateTime object
     * @param pDateTime $datetime1 The first pDateTime object to compare
     * @param pDateTime $datetime2 The second pDateTime object to compare
     * @return integer Returns 0 if they are the same, -1 if $datetime1 < $datetime2
     *                 and 1 if $datetime1 > $datetime2.
     * @since 1.0-sofia
     */
    public function compare($datetime1, $datetime2) {
        $dateComp = new pDateComparator();
        $result = $dateComp->compare($datetime1, $datetime2);
        if($result === 0){
            $timeComp = new pTimeComparator();
            $result = $timeComp->compare($datetime1->time(), $datetime2->time());
        }
        return $result;
    }
    
}