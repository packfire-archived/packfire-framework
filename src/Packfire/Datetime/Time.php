<?php
namespace Packfire\DateTime;

use DateTimeComponent;
use TimeComparator;
use Packfire\Collection\Sort\IComparable;

/**
 * Time class
 * 
 * Time of the day
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class Time extends DateTimeComponent implements IComparable {
    
    /**
     * Hour of the day (0 to 23), 24-hour format
     * @var integer
     * @since 1.0-sofia
     */
    protected $hour = 0;
    
    /**
     * Minutes of the hour (0 to 59)
     * @var integer
     * @since 1.0-sofia
     */
    protected $minute = 0;
    
    /**
     * Seconds of the minute (0 to 59)
     * @var integer
     * @since 1.0-sofia
     */
    protected $second = 0;
    
    /**
     * milliseconds
     * @var integer
     * @since 1.0-sofia
     */
    protected $millisecond = 0;
    
    /**
     * Create a new Time object
     * @param integer $hour (optional) The hour component. Defaults to 0.
     * @param integer $minute (optional) The minute component. Defaults to 0.
     * @param integer $second (optional) The second component. Defaults to 0.
     * @param double|integer $millisecond (optional) The millisecond component.
     *                                    Defaults to 0.
     * @since 1.0-sofia
     */
    function __construct($hour = 0, $minute = 0, $second = 0, $millisecond = 0) {
        $this->hour($hour);
        $this->minute($minute);
        $this->second($second);
        $this->millisecond($millisecond);
    }

    /**
     * Get or set the millisecond component of the time
     * @param double|integer $millisecond (optional) Set the millisecond value
     *                          with an double or integer from 0 to 999 that 
     *                          represents the 1000 milliseconds in a second.
     * @return double|integer Returns the millisecond component of the time
     * @since 1.0-sofia
     */
    public function millisecond($millisecond = null){
        if(func_num_args() == 1){
            if($millisecond != $this->millisecond){
                $millisecond = $this->processNextComponent($millisecond,
                                            'second', 1000);
                $this->millisecond = (int)$millisecond;
            }
        }
        return $this->millisecond;
    }

    /**
     * Get or set the second component of the time
     * @param integer $second (optional) Set the second value with an integer 
     *                   from 0 to 59 that represents the 60 seconds in 1 minute
     * @return integer Returns the second component of the time
     * @since 1.0-sofia
     */
    public function second($second = null){
        if(func_num_args() == 1){
            if($second != $this->second){
                $second = $this->processNextComponent($second, 'minute', 60);
                $this->second = (int)$second;
            }
        }
        return $this->second;
    }

    /**
     * Get or set the minute component of the time
     * @param integer $minute (optional) Set the minute value with an integer
     *                        from 0 to 59 that represents the 60 minute in 1
     *                        hour.
     * @return integer Returns the minute component of the time
     * @since 1.0-sofia
     */
    public function minute($minute = null){
        if(func_num_args() == 1){
            if($minute != $this->minute){
                $minute = $this->processNextComponent($minute, 'hour', 60);
                $this->minute = (int)$minute;
            }
        }
        return $this->minute;
    }

    /**
     * Get or set the hour component of the time
     * @param integer $hour (optional) Set the hour value with an integer. If
     *                      the number of hours is more than 24 or less than 0,
     *                      the method will calculate the to a 24-hour value.
     * @return integer Returns the hour component of the time
     * @since 1.0-sofia
     */
    public function hour($hour = null){
        if(func_num_args() == 1){
            if($hour != $this->hour){
                if($hour >= 24){
                    $addNext = (int)floor($hour / 24);
                    $hour -= $addNext * 24;
                }elseif($hour < 0){
                    $subNext = (int)floor(abs($hour) / 24);
                    $hour = 24 + ($hour + $subNext * 24);
                }
                $this->hour = (int)$hour;
            }
        }
        return $this->hour;
    }
    
    /**
     * Get the total number of seconds from midnight 00:00:00.0
     * @return double|integer Returns the total number of seconds from midnight.
     * @since 1.0-sofia
     */
    public function totalSeconds(){
        return $this->hour * 3600 + $this->minute * 60
                + $this->second + ($this->millisecond / 1000);
    }
    
    /**
     * Add another time to this time. If the sum of the two time object passes
     * 24 hours (i.e. a day), the result will be the time next day.
     * @param Time $time The amount of time to add.
     * @return Time Returns the resulting Time from the addition operation.
     * @since 1.0-sofia
     */
    public function add($time){
        $temp = new self($this->hour, $this->minute,
                $this->second, $this->millisecond);
        
        $temp->millisecond($temp->millisecond + $time->millisecond);
        $temp->second($temp->second + $time->second);
        $temp->minute($temp->minute + $time->minute);
        $temp->hour($temp->hour + $time->hour);
        
        return $temp;
    }

    /**
     * Subtract some time from this time. If the result is negative, it will
     * return the time of the previous day.
     * @param Time $time The amount of time to deduct
     * @return Time Returns the resulting Time from the subtract operation
     * @since 1.0-sofia
     */
    public function subtract($time){
        $temp = new self($this->hour, $this->minute,
                $this->second, $this->millisecond);
        
        $temp->millisecond($temp->millisecond - $time->millisecond);
        $temp->second($temp->second - $time->second);
        $temp->minute($temp->minute - $time->minute);
        $temp->hour($temp->hour - $time->hour);
        
        return $temp;
    }
    
    /**
     * Compare this Time object with another Time object
     * @param Time $another The other Time object to compare with
     * @return integer Returns 0 if they are the same, -1 if $this < $another
     *                 and 1 if $this > $another.
     * @since 1.0-sofia
     */
    public function compareTo($another) {
        $comparator = new TimeComparator();
        return $comparator->compare($this, $another);
    }

}