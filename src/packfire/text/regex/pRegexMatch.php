<?php

/**
 * Represents a regular expression match created from pRegex
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.text.regex
 * @since 1.0-sofia
 */
class pRegexMatch {

    /**
     * The regular expression pRegex object that this match originated from
     * @var pRegex
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
     * Create a new pRegexMatch
     * @param pRegex $regex The pRegex object that created this match
     * @param string $match The matching string
     * @since 1.0-sofia
     */
    public function __construct($regex, $match){
        $this->regex = $regex;
        $this->match = $match;
    }

    /**
     * Get the regular expression pRegex object that this match originated from
     * @return pRegex Returns the regular expression object
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
