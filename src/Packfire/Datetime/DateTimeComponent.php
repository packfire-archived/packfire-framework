<?php
namespace Packfire\DateTime;

/**
 * DateTimeComponent class
 * 
 * Date/Time Component abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.datetime
 * @since 1.0-sofia
 */
abstract class DateTimeComponent {
    
    /**
     * Process the next component
     * @param integer $value
     * @param string $next
     * @param integer $max
     * @return integer
     * @internal
     * @since 1.0-sofia
     */
    protected function processNextComponent($value, $next, $max){
        if($value >= $max){
            $addNext = (int)floor($value / $max);
            $this->$next($this->$next() + $addNext);
            $value -= $addNext * $max;
        }elseif($value < 0){
            $subNext = (int)floor(abs($value) / $max);
            $this->$next($this->$next() - $subNext - 1);
            $value = $max + ($value + ($subNext * $max));
        }
        return $value;
    }
    
}