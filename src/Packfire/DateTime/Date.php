<?php
namespace Packfire\DateTime;

use DateTimeComponent;
use Packfire\Collection\Sort\IComparable;
use DateTime;
use DateComparator;

/**
 * Date class
 * 
 * Grerogian Calendar Date
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
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
     * Get the total number of days in this date since the 1 AD
     * @return integer Returns an integer of the total number of days
     * @since 1.0-sofia
     */
    public function totalDays(){
        $deltaYear = $this->year - 1;
        $leapCount = round(($deltaYear / 4) 
                - ($deltaYear / 100) 
                + ($deltaYear / 400));
        $monthDays = 0;
        for($month = 1; $month <= $this->month; ++$month){
            $monthDays += DateTime::daysInMonth($month, $this->year);
        }
        return $this->day 
                + $monthDays
                + $leapCount * 366 
                + ($this->year - $leapCount) * 365;
    }
    
    /**
     * Add another date to this date.
     * @param TimeSpan $timeSpan The amount of time to add
     * @return Date The resulting date from the addition operation
     * @since 1.0-sofia
     */
    public function add($timeSpan){
        $temp = new self($this->year, $this->month, $this->day);
        
        $temp->day($temp->day + $timeSpan->day());
        
        return $temp;
    }
    
    /**
     * Subtract some date from this date. 
     * @param TimeSpan $timeSpan The amount of time to deduct
     * @return Date The resulting date from the subtract operation
     * @since 1.0-sofia
     */
    public function subtract($timeSpan){
        $temp = new self($this->year, $this->month, $this->day);
        
        $temp->day($temp->day - $timeSpan->day());
        
        return $temp;
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