<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Text\Regex;

/**
 * Represents a regular expression match created from Regex
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Text\Regex
 * @since 1.0-sofia
 */
class Match {

    /**
     * The regular expression Regex object that this match originated from
     * @var Regex
     * @since 1.0-sofia
     */
    private $regex;

    /**
     * The text that matched the pattern
     * @var string
     * @since 1.0-sofia
     */
    private $match;

    /**
     * Create a new Match
     * @param Regex $regex The Regex object that created this match
     * @param string $match The matching string
     * @since 1.0-sofia
     */
    public function __construct($regex, $match){
        $this->regex = $regex;
        $this->match = $match;
    }

    /**
     * Get the regular expression Regex object that this match originated from
     * @return Regex Returns the regular expression object
     * @since 1.0-sofia
     */
    public function regex(){
        return $this->regex;
    }

    /**
     * Get the text that matched the pattern
     * @return string Returns the text that matched the pattern
     * @since 1.0-sofia
     */
    public function match(){
        return $this->match;
    }

}
