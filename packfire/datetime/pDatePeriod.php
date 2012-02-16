<?php
pload('pDateTime');

/**
 * pDatePeriod Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
 * @since 1.0-sofia
 */
class pDatePeriod implements Iterator {
    
    /**
     * The interval
     * @var pTimeSpan
     * @since 1.0-sofia
     */
    private $interval;
    
    /**
     * The start date
     * @var pDateTime 
     * @since 1.0-sofia
     */
    private $startDate;
    
    /**
     * Number of occurances
     * @var integer
     * @since 1.0-sofia
     */
    private $occurances;
    
    /**
     * The end date
     * @var pDateTime
     * @since 1.0-sofia
     */
    private $endDate;
    
    /**
     * Counter
     * @var integer
     * @since 1.0-sofia
     */
    private $count;
    
    /**
     * The working date
     * @var pDateTime
     * @since 1.0-sofia
     */
    private $workingDate;
    
    /**
     * Create a new pDatePeriod object
     * @param pDateTime $startDate The start date
     * @param pTimeSpan $interval The interval between dates
     * @param integer|pDateTime $occurances/$endDate The number of occurance or end date
     * @since 1.0-sofia
     */
    public function __construct($startDate, $interval, $endDate){
        $this->startDate = $startDate;
        $this->interval = $interval;
        if($endDate instanceof pDateTime){
            $this->endDate = $endDate;
        }else{
            $this->occurances = $endDate;
        }
    }
    
    /**
     * Get the start date
     * @return pDateTime Returns the start date
     * @since 1.0-sofia
     */
    public function startDate(){
        return $this->startDate;
    }
    
    /**
     * Get the interval between dates
     * @return pTimeSpan Returns the interval
     * @since 1.0-sofia 
     */
    public function interval(){
        return $this->interval;
    }
    
    /**
     * Get the end date
     * @return pDateTime Returns the end date if set, NULL otherwise.
     * @since 1.0-sofia
     */
    public function endDate(){
        return $this->endDate;
    }
    
    /**
     * Get the number of occurrances
     * @return integer Returns the number of occurrances if set, NULL otherwise.
     * @since 1.0-sofia 
     */
    public function occurrances(){
        
    }
    
    public function current() {
        return $this->workingDate;
    }

    public function key() {
        return $this->count;
    }

    public function next() {
        $this->workingDate = $this->workingDate->add($this->interval);
        ++$this->count;
    }

    public function rewind() {
        $this->workingDate = $this->startDate->add(new pTimeSpan());
        $this->count = 0;
    }

    public function valid() {
        return ($this->occurances !== null && $this->count < $this->occurances) ||
            ($this->endDate instanceof pDateTime &&
                $this->endDate->compareTo($this->workingDate) === 1);
    }
    
}