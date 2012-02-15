<?php
pload('pTime');

/**
 * pTimeSpan Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
 * @since 1.0-sofia
 */
class pTimeSpan extends pTime {

    /**
     * Number of d
     * @var integer
     * @since 1.0-sofia
     */
    private $day = 0;

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
                $this->hour = (int)$hour;
            }
        }
        return $this->hour;
    }

    /**
     * Get or set the day component of the time
     * @param integer $dat (optional) Set the day value with an integer. Must
     *                     be an non-negative number.
     * @return integer Returns the day component of the time
     * @since 1.0-sofia
     */
    public function day($day = null){
        if(func_num_args() == 1){
            if($day != $this->day){
                if($day < 0){
                    // todo throw exception cannot less than zero
                }
                $this->day = (int)$day;
            }
        }
        return $this->day;
    }

    /**
     * Get the total number of seconds in this time span
     * @return integer|double
     */
    public function totalSeconds(){
        return $this->day * 86400.0 
                + $this->hour() * 3600.0 
                + $this->minute() * 60.0 
                + $this->second
                + ($this->millisecond() / 1000.0);
    }

    /**
     * Get the total number of minutes in this time span
     * @return integer|double
     */
    public function totalMinutes(){
        return $this->day * 1440 
                + $this->hour() * 60.0 
                + $this->minute() 
                + ($this->second() / 60.0) 
                + ($this->millisecond() / 60000.0);
    }

    /**
     * Get the total number of hours in this time span
     * @return integer|double
     */
    public function totalHours(){
        return $this->day * 24 
                + $this->hour() 
                + ($this->minute() / 60.0) 
                + ($this->second() / 3600.0) 
                + ($this->millisecond() / 3600000.0);
    }

    /**
     * Get the total number of days in this time span
     * @return integer|double
     */
    public function totalDays(){
        return $this->day 
                + ($this->hour() / 24.0) 
                + ($this->minute() / 1440.0) 
                + ($this->second() / 86400.0) 
                + ($this->millisecond() / 86400000.0);
    }
    
    /**
     * Add another time span to this time span.
     * @param pTimeSpan $time The amount of time to add.
     * @return pTimeSpan Returns the resulting pTimeSpan from the addition operation.
     */
    public function add($time){
        $temp = new self();
        
        $temp->millisecond($this->millisecond + $time->millisecond);
        $temp->second($this->second + $time->second);
        $temp->minute($this->minute + $time->minute);
        $temp->hour($this->hour + $time->hour);
        
        return $temp;
    }

    /**
     * Subtract some time span from this time span.
     * @param pTimeSpan $time The amount of time to deduct
     * @return pTimeSpan Returns the resulting pTimeSpan from the subtract operation
     * @since 1.0-sofia
     */
    public function subtract($time){
        $temp = new self();
        
        if($time->totalSeconds() > $this->totalSeconds()){
            // todo: throw exception cannot be negative
        }else{
            $temp->millisecond($this->millisecond - $time->millisecond);
            $temp->second($this->second - $time->second);
            $temp->minute($this->minute - $time->minute);
            $temp->hour($this->hour - $time->hour);
            $temp->day($this->day - $time->day);
        }
        
        return $temp;
    }
    
}