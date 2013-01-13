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

use Packfire\DateTime\DateTime;
use Packfire\DateTime\TimeSpan;

/**
 * A date period representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class DatePeriod implements \Iterator {

    /**
     * The interval
     * @var TimeSpan
     * @since 1.0-sofia
     */
    private $interval;

    /**
     * The start date
     * @var DateTime
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
     * @var DateTime
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
     * @var DateTime
     * @since 1.0-sofia
     */
    private $workingDate;

    /**
     * Create a new DatePeriod object
     * @param DateTime $startDate The start date
     * @param TimeSpan $interval The interval between dates
     * @param integer|DateTime $endDateOrOccurances The number of occurance
     *          or end date
     * @since 1.0-sofia
     */
    public function __construct($startDate, $interval, $endDateOrOccurances){
        $this->startDate = $startDate;
        $this->interval = $interval;
        if($endDateOrOccurances instanceof DateTime){
            $this->endDate = $endDateOrOccurances;
        }else{
            $this->occurances = $endDateOrOccurances;
        }
    }

    /**
     * Get the start date
     * @return DateTime Returns the start date
     * @since 1.0-sofia
     */
    public function startDate(){
        return $this->startDate;
    }

    /**
     * Get the interval between dates
     * @return TimeSpan Returns the interval
     * @since 1.0-sofia
     */
    public function interval(){
        return $this->interval;
    }

    /**
     * Get the end date
     * @return DateTime Returns the end date if set, NULL otherwise.
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
        $this->workingDate = $this->startDate->add(new TimeSpan());
        $this->count = 0;
    }

    public function valid() {
        return ($this->occurances !== null && $this->count < $this->occurances) ||
            ($this->endDate instanceof DateTime &&
                $this->endDate->compareTo($this->workingDate) === 1);
    }

}