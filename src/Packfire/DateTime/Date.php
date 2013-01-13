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

use Packfire\DateTime\DateTimeComponent;
use Packfire\Collection\Sort\IComparable;
use Packfire\DateTime\DateTime;
use Packfire\DateTime\DateComparator;

/**
 * Gregorian Calendar Date
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class Date extends DateTimeComponent implements IComparable {

    /**
     * Day of the month (1 to 31)
     * @var integer
     * @since 1.0-sofia
     */
    protected $day = 0;

    /**
     * Month of the year (1 to 12)
     * @var integer
     * @since 1.0-sofia
     */
    protected $month = 0;

    /**
     * The year
     * @var integer
     * @since 1.0-sofia
     */
    protected $year = 0;
    
    /**
     * Create a new Date object
     * @param integer $year Set the year component
     * @param integer $month Set the month component
     * @param integer $day Set the day component
     * @since 1.0-sofia
     */
    public function __construct($year, $month, $day){
        $this->year($year);
        $this->month($month);
        $this->day($day);
    }
    
    /**
     * Get or set the day component of the date
     * @param integer $day (optional) Set the day component with an integer from
     *                     1 to 31 representing the days of a month.
     * @return integer Returns the day component value
     * @since 1.0-sofia
     */
    public function day($day = null){
        if(func_num_args() == 1){
            if($day != $this->day){
                $day--;
                $day = $this->processNextComponent($day, 'month',
                        DateTime::daysInMonth($this->month, $this->year)) + 1;
                $this->day = $day + 0;
            }
        }
        return $this->day;
    }

    /**
     * Get or set the month component of the date
     * @param integer $month (optional) Set the the month component with an
     *                       integer from 1 to 12 representing the various months.
     * @return integer Returns the month component value
     * @since 1.0-sofia
     */
    public function month($month = null){
        if(func_num_args() == 1){
            if($month != $this->month){
                $month--;
                $month = $this->processNextComponent($month, 'year', 12) + 1;
                $this->month = $month + 0;
            }
        }
        return $this->month;
    }
    
    /**
     * Get or set the year component of the date
     * @param integer $year (optional) Set the year component with an integer
     *                      that represents the year
     * @return integer Returns the year component
     * @since 1.0-sofia
     */
    public function year($year = null){
        if(func_num_args() == 1){
            if($year != $this->year){
                $this->year = $year + 0;
            }
        }
        return $this->year;
    }
    
    /**
     * Get the short year representation. i.e. 2011 returns 11 and 1986 returns 86.
     * @return integer Returns the short year value
     * @since 1.0-sofia
     */
    public function shortYear(){
        return $this->year % 100;
    }
    
    /**
     * Get the century in which the date is in. 
     * @return integer Returns an integer
     * @since 1.0-sofia
     */
    public function century(){
        $cen = (int)($this->year / 100);
        if($this->year % 100 > 0){
            ++$cen;
        }
        return $cen;
    }
    
    /**
     * Create date from number of days
     * @param integer $days The number of days
     * @return Date Returns the created Date object
     * @since 2.0.0
     */
    public static function fromDays($days){
        $year = (int)((10000* $days + 14780) / 3652425);
        $d3 = $days - (365* $year + (int)($year / 4) - (int)($year / 100) + (int)($year / 400));
        if($d3 < 0){
            $year--;
            $d3 = $days - (365* $year + (int)($year / 4) - (int)($year / 100) + (int)($year / 400));
        }
        $tempM = (int)((100 * $d3 + 52) / 3060);
        $month = ($tempM + 2) % 12 + 1;
        $year += (int)(($tempM + 2) / 12);
        $day = $d3 - (int)(($tempM * 306 + 5) / 10) + 1;
        return new self($year, $month, $day);
    }
    
    /**
     * Get the total number of days in this date
     * 
     * Algorithm from: http://alcor.concordia.ca/~gpkatch/gdate-algorithm.html
     * 
     * @return integer Returns an integer of the total number of days
     * @since 1.0-sofia
     */
    public function totalDays(){
        $month = ($this->month + 9) % 12;
        $year = $this->year - (int)($month / 10);
        return 365 * $year + (int)($year / 4) - (int)($year / 100) + (int)($year / 400)
                + (int)(($month * 306 + 5) / 10) + $this->day - 1;
    }
    
    /**
     * Add another date to this date.
     * @param TimeSpan $timeSpan The amount of time to add
     * @return Date The resulting date from the addition operation
     * @since 1.0-sofia
     */
    public function add($timeSpan){
        return self::fromDays($this->totalDays() + $timeSpan->day());
    }
    
    /**
     * Subtract some date from this date. 
     * @param TimeSpan $timeSpan The amount of time to deduct
     * @return Date The resulting date from the subtract operation
     * @since 1.0-sofia
     */
    public function subtract($timeSpan){
        return self::fromDays($this->totalDays() - $timeSpan->day());
    }
    
    /**
     * Compare this Date object with another Date object
     * @param Date $another The other Date object to compare with
     * @return integer Returns 0 if they are the same, -1 if $this < $another
     *                 and 1 if $this > $another.
     * @since 1.0-sofia
     */
    public function compareTo($another) {
        $comparator = new DateComparator();
        return $comparator->compare($this, $another);
    }
    
}