<?php
pload('IValidator');

/**
 * Match Validator
 * checks if value matches values provided
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.0-sofia
 */
class pRegexValidator implements IValidator {
    
    /**
     * The regular expression object
     * @var pRegex
     * @since 1.0-sofia
     */
    private $regex;
    
    /**
     * Create a new regular expression validator pRegexValidator
     * @param string $match The regular expression
     * @since 1.0-sofia
     */
    public function __construct($match){
        $this->regex = new pRegex($match);
    }
    
    /**
     * Validate the value
     * @param mixed $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function validate($value) {
        $list = $this->regex->match($value);
        return $list->count() > 0;
    }
    
}