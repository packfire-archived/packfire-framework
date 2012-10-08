<?php
namespace Packfire\DateTime;

use Time;
use Packfire\Exception\InvalidArgumentException;

/**
 * TimeSpan class
 * 
 * A period of time.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class TimeSpan extends Time {

    /**
     * Number of days
     * @var integer
     * @since 1.0-sofia
     */
    protected $day = 0;
    
    /**
     * Create a new TimeSpan object
     * @param integer $seconds (optional) The number of seconds to initialize
     *                         the time span with.
     * @since 1.0-sofia
     */
    public function __construct($seconds = null){
        if(func_num_args() == 1){
            $this->day(floor($seconds / 86400));
            $seconds -= $this->day() * 86400;
            $this->hour(floor($seconds / 3600));
            $seconds -= $this->hour() * 3600;
            $this->minute(floor($seconds / 60));
            $seconds -= $this->minute() * 60;
            $this->second($seconds);
        }
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
                $hour = $this->processNextComponent($hour, 'day', 24);
                $this->hour = $hour + 0;
            }
        }
        return $this->hour;
    }

    /**
     * Get or set the day component of the time
     * @param integer $day (optional) Set the day value with an integer. Must
     *                     be an non-negative number.
     * @return integer Returns the day component of the time
     * @throws pInvalidArgumentException Thrown when a negative $day is provided.
     * @since 1.0-sofia
     */
    public function day($day = null){
        if(func_num_args() == 1){
            if($day != $this->day){
                if($day < 0){
                    throw new InvalidArgumentException('pTimeSpan::day', 'day', 'non-negative', $day);
                }
                $this->day = $day + 0;
            }
        }
        return $this->day;
    }

    /**
     * Get the total number of seconds in this time span
     * @return integer|double Returns the total number of seconds
     * @since 1.0-sofia
     */
    public function totalSeconds(){
        return $this->day * 86400.0 
                + $this->hour * 3600.0 
                + $this->minute * 60.0 
                + $this->second
                + ($this->millisecond / 1000.0);
    }

    /**
     * Get the total number of minutes in this time span
     * @return integer|double Returns the total number of minutes
     * @since 1.0-sofia
     */
    public function totalMinutes(){
        return $this->day * 1440 
                + $this->hour * 60.0 
                + $this->minute 
                + ($this->second / 60.0) 
                + ($this->millisecond / 60000.0);
    }

    /**
     * Get the total number of hours in this time span
     * @return integer|double Returns the total number of hours
     * @since 1.0-sofia
     */
    public function totalHours(){
        return $this->day * 24 
                + $this->hour 
                + ($this->minute / 60.0) 
                + ($this->second / 3600.0) 
                + ($this->millisecond / 3600000.0);
    }

    /**
     * Get the total number of days in this time span
     * @return integer|double Returns the total number of days
     * @since 1.0-sofia
     */
    public function totalDays(){
        return $this->day 
                + ($this->hour / 24.0) 
                + ($this->minute / 1440.0) 
                + ($this->second / 86400.0) 
                + ($this->millisecond / 86400000.0);
    }
    
    /**
     * Add another time span to this time span.
     * @param TimeSpan $time The amount of time to add.
     * @return TimeSpan Returns the resulting pTimeSpan from the addition operation.
     * @since 1.0-sofia
     */
    public function add($time){
        $temp = new self();
        
        $temp->day($this->day + $time->day);
        $temp->hour($this->hour + $time->hour);
        $temp->minute($this->minute + $time->minute);
        $temp->second($this->second + $time->second);
        $temp->millisecond($this->millisecond + $time->millisecond);
        
        return $temp;
    }

    /**
     * Subtract some time span from this time span.
     * @param TimeSpan $time The amount of time to deduct
     * @return TimeSpan Returns the resulting pTimeSpan from the subtract operation
     * @since 1.0-sofia
     */
    public function subtract($time){
        $temp = new self();
        
        $temp->day($this->day - $time->day);
        $temp->hour($this->hour - $time->hour);
        $temp->minute($this->minute - $time->minute);
        $temp->second($this->second - $time->second);
        $temp->millisecond($this->millisecond - $time->millisecond);
        
        return $temp;
    }
    
}