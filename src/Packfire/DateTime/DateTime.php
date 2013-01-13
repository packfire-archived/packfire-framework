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

use Packfire\DateTime\Time;
use Packfire\DateTime\Date;
use Packfire\DateTime\TimeSpan;
use Packfire\DateTime\DateTimeFormat;
use Packfire\Exception\InvalidArgumentException;
use Packfire\DateTime\DateTimeComparator;
use Packfire\Text\Format\IFormattable;

/**
 * A date and time representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
class DateTime extends Date implements IFormattable {
    
    /**
     * The time component
     * @var Time
     * @since 1.0-sofia
     */
    protected $time;
    
    /**
     * The timezone component of the RaiseDateTime
     * @var double
     * @since 1.0-sofia
     */
    protected $timezone = 0;
    
    /**
     * Create a new DateTime object
     * @param integer $year The year component
     * @param integer $month The month component
     * @param integer $day The day component
     * @param integer $hour (optional) The hour component. Defaults to 0.
     * @param integer $minute (optional) The minute component. Defaults to 0.
     * @param integer $second (optional) The second component. Defaults to 0.
     * @param double|integer $millisecond (optional) The millisecond component.
     *                                    Defaults to 0.
     * @since 1.0-sofia
     */
    public function __construct($year, $month, $day, $hour = 0, $minute = 0, $second = 0, $millisecond = 0){
        $this->time = new Time($hour, $minute, $second, $millisecond);
        parent::__construct($year, $month, $day);
    }

    /**
     * Get or set the timezone component of the DateTime
     * @param double $timezone (optional) An integer that represents number of hours in
     *                         terms of time zone offset (1.5 for 1 hour 30 minutes)
     * @return double Returns the DateTime's timezone component
     * @since 1.0-sofia
     */
    public function timezone($timezone = null){
        if(func_num_args() == 1 && $this->timezone != $timezone){
            $dt = self::convertTimezone($this, $timezone);
            $this->year = $dt->year();
            $this->month = $dt->month;
            $this->day = $dt->day;
            $this->time = $dt->time;
            $this->timezone = $dt->timezone;
        }
        return $this->timezone;
    }
    
    /**
     * Get the time of the day.
     * 
     * Note: This method returns a copy of the time. Changes made to the Time object
     * returned by this method will not be reflected in the DateTime object.
     * 
     * @return Time Returns the time component
     * @since 1.0-sofia
     */
    public function time(){
        $time = new Time($this->time->hour(), $this->time->minute(),
                $this->time->second(), $this->time->millisecond());
        return $time;
    }
    
    /**
     * Get the day of the week based on Zeller's Congruence
     * @return integer Returns one of the DaysOfWeek constants
     * @since 1.0-sofia
     */
    public function dayOfWeek(){
        $month = $this->month();
        $year = $this->year();
        if($month < 3){
            $month += 12;
            $year--;
        }
        $century = (int)($year / 100);
        $year %= 100;
        $t = $this->day() + (int)(26 * ($month + 1) / 10) 
                + $year + (int)($year / 4) 
                + (int)($century / 4) - 2 * $century;
        $dow = $t % 7;
        if($dow < 0){
            $dow += 7;
        }
        return $dow;
    }

    /**
     * Check if a year is leap year
     * @param integer|Date $year The year or Date to check
     * @return boolean Returns true if the year is a leap year, false otherwise.
     * @since 1.0-sofia
     */
    public static function isLeapYear($year){
        if($year instanceof Date){
            $year = $year->year();
        }
        return (($year % 400 == 0) || (($year % 4 == 0) && ($year % 100 != 0)));
    }

    /**
     * Get the number of days in a month, considering whether the year is a leap year or not
     * @param integer|Date $month The month of the year (1-12) or the Date to check
     * @param integer $year (optional) The year. Ignored when first parameter is Date
     * @return integer The number of days in that month of that year
     * @throws InvalidArgumentException
     * @since 1.0-sofia
     */
    public static function daysInMonth($month, $year = null){
        if($month instanceof Date){
            $year = $month->year();
            $month = $month->month();
        }elseif(func_num_args() == 1){
            $year = self::now()->year();
        }
        if($month < 1 || $month > 12){
            throw new InvalidArgumentException(
                    'DateTime::daysInMonth', '$month', 'from 1 to 12.', $month
                );
        }
        $mapping = array(
            31, (self::isLeapYear($year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31
        );
        if(array_key_exists($month - 1, $mapping)){
            return $mapping[$month - 1];
        }
    }

    /**
     * Get the DateTime of this exact instant
     * @return DateTime Returns the date time
     * @since 1.0-sofia
     */
    public static function now() {
        $now = self::fromTimestamp(time());
        return $now;
    }

    /**
     * Convert a UNIX Epoch Timestamp to DateTime
     * 
     * Note: that when converting from a seconds-based timestamp to DateTime,
     * timezone and millisecond information cannot be captured and will be both set to 0.
     * 
     * @param integer $ts The UNIX Epoch Timestamp to convert. 
     * @return DateTime Returns the date time representation of the converted
     *                   time span.
     * @since 1.0-sofia
     */
    public static function fromTimestamp($ts) {
        if (!$ts) {
            $ts = time();
        }
        $dt = new DateTime(
                gmdate('o', $ts) + 0,
                gmdate('n', $ts) + 0,
                gmdate('j', $ts) + 0,
                gmdate('G', $ts) + 0,
                gmdate('i', $ts) + 0,
                gmdate('s', $ts) + 0
            );
        
        return $dt;
    }

    /**
     * Convert to UNIX Epoch Timestamp at GMT
     * @param boolean $convert (optional) Set whether the DateTime is converted
     *                         to GMT before returning the number of seconds
     *                         from the UNIX Epoch. Defaults to true.
     * @return integer Returns the UNIX epoch timestamp.
     * @since 1.0-sofia
     */
    public function toTimestamp($convert = true) {
        $neutral = $this;
        if($convert){
            $neutral = self::convertTimezone($neutral, 0);
        }
        $ts = gmmktime($neutral->time->hour(), $neutral->time->minute(),
                $neutral->time->second(), $neutral->month(),
                $neutral->day(), $neutral->year());
        return $ts;
    }

    /**
     * Change the timezone of a DateTime
     * @param DateTime $dateTime The date time object
     * @param double $target The destination timezone to set the date time to.
     *                       In the form of hours from -12 to 12
     * @return DateTime Returns the date time in the converted time zone
     * @throws InvalidArgumentException
     * @since 1.0-sofia
     */
    public static function convertTimezone($dateTime, $target){
        if($target < -12 || $target > 12){
            throw new InvalidArgumentException(
                    'DateTime::convertTimezone', '$target', 'from -12 to 12.', $target
                );
        }else{
            $diff = $dateTime->timezone - $target;
            $ts = new TimeSpan(abs($diff) * 3600);
            if($diff > 0){
                $dt = $dateTime->subtract($ts);
            }else{
                $dt = $dateTime->add($ts);
            }
            $dt->timezone = $target + 0;
            return $dt;
        }
    }

    /**
     * Convert a string representation into DateTime
     * @param string $s The string to convert
     * @return DateTime Returns the date time converted from string
     * @since 1.0-sofia
     */
    public static function fromString($s) {
        $reset = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $time = strtotime($s);
        date_default_timezone_set($reset);
        return self::fromTimestamp($time);
    }

    /**
     * Create an ISO8601 DateTime string
     * @return string Returns the ISO8601 representation
     * @link http://www.iso.org/iso/date_and_time_format
     * @since 1.0-sofia
     */
    public function toISO8601() {
        return gmdate(DateTimeFormat::ISO8601, $this->toTimestamp());
    }

    /**
     * Create an RFC822 (updated by RFC 1123) DateTime formatted string
     * @link http://www.freesoft.org/CIE/RFC/1945/14.htm
     * @return string Returns the RFC822 string representation
     * @since 1.0-sofia
     */
    public function toRFC822(){
       return gmdate(DateTimeFormat::RFC822, $this->toTimestamp());
    }
    
    /**
     * Format the date time
     * @param string $format The format
     * @return string Returns the date and time formatted
     * @link http://php.net/date
     * @since 1.0-sofia
     */
    public function format($format){
        return gmdate($format, $this->toTimestamp());
    }

    /**
     * Add a period of time TimeSpan to the current date time
     * @param TimeSpan $timespan The amount of time to add.
     * @return DateTime The resulting DateTime that resulted from the add operation.
     * @since 1.0-sofia
     */
    public function add($timespan){
        $date = parent::add($timespan);
        $datetime = new self($date->year(), $date->month(), $date->day());
        $time = $this->time->add($timespan);
        $diff = $time->totalSeconds() - $datetime->time->totalSeconds();
        if(abs($diff) > 86400){ // if difference is more than 24hrs
            if($diff > 0){
                $datetime->day($datetime->day + 1);
            }else{
                $datetime->day($datetime->day - 1);
            }
        }
        $datetime->time = $time;
        return $datetime;
    }

    /**
     * Subtract another Date or TimeSpan from the current one
     * @param Date|TimeSpan $period The amount of time to subtract
     * @return TimeSpan|Date The result of the subtract operation. If $period
     *                         is a Date, TimeSpan will be returned. If $period
     *                         is a TimeSpan, DateTime will be returned instead.
     * @since 1.0-sofia
     */
    public function subtract($period){
        $tspan = $period;
        if($period instanceof Date){
            $tspan = new TimeSpan();
            $tspan->day($period->totalDays());
        }
        $date = parent::subtract($tspan);
        $datetime = new self($date->year(), $date->month(), $date->day());
        $datetime->time = $this->time->subtract(new Time());
        $timeWork = null;
        if($period instanceof DateTime){
            $timeWork = $period->time;
        }elseif($period instanceof TimeSpan){
            $timeWork = $period;
        }
        if($timeWork){
            $time = $this->time->subtract($timeWork);
            $diff = $this->time->totalSeconds() - $time->totalSeconds();
            if($diff > 86400 || $diff < -0){ // if difference is more than 24hrs
                if($diff > 0){
                    $datetime->day($datetime->day + 1);
                }else{
                    $datetime->day($datetime->day - 1);
                }
            }
            $datetime->time = $time;
        }
        $result = $datetime;
        
        if($period instanceof Date){
            $result = new TimeSpan();
            $result->day($datetime->totalDays());
            $result->hour($datetime->time()->hour());
            $result->minute($datetime->time()->minute());
            $result->second($datetime->time()->second());
            $result->millisecond($datetime->time()->millisecond());
        }
        return $result;
    }
    
    /**
     * Calculate the age based on birthday and current date
     * @param Date $birthday The person's birthdate
     * return integer Returns the calculated age
     * @since 1.0-sofia
     */
    public static function calculateAge($birthday){
        $now = DateTime::now();
        $years = $now->year() - $birthday->year();
        
        if($now->month() < $birthday->month() ||
                ($now->month() >= $birthday->month() && $now->day() < $birthday->day())){
            // birthday hasn't pass, so subtract one.
            --$years;
        }
        
        return $years;
    }

    /**
     * Get the current UNIX Timestamp with microseconds
     * @return double Returns the UNIX timestamp with microseconds
     * @since 1.0-sofia
     */
    public static function microtime(){
        $usec = 0;
        $sec = 0;
        list($usec, $sec) = explode(' ', microtime());
        return ((double)$usec + (double)$sec);
    }
    
    /**
     * Compare this DateTime object with another DateTime object
     * @param DateTime $another The other DateTime object to compare with
     * @return integer Returns 0 if they are the same, -1 if $this < $another
     *                 and 1 if $this > $another.
     * @since 1.0-sofia
     */
    public function compareTo($another) {
        $comparator = new DateTimeComparator();
        return $comparator->compare($this, $another);
    }
    
}