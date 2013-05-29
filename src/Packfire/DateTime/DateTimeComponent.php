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

/**
 * Date/Time Component abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\DateTime
 * @since 1.0-sofia
 */
abstract class DateTimeComponent
{
    /**
     * Process the next component
     * @param  integer $value
     * @param  string  $next
     * @param  integer $max
     * @return integer
     * @internal
     * @since 1.0-sofia
     */
    protected function processNextComponent($value, $next, $max)
    {
        if ($value >= $max) {
            $addNext = (int) floor($value / $max);
            $this->$next($this->$next() + $addNext);
            $value -= $addNext * $max;
        } elseif ($value < 0) {
            $subNext = (int) floor(abs($value) / $max);
            $this->$next($this->$next() - $subNext - 1);
            $value = $max + ($value % $max);
        }

        return $value;
    }

}
