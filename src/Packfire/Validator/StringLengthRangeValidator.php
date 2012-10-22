<?php
namespace Packfire\Validator;

use Packfire\Validator\NumericRangeValidator;

/**
 * StringLengthRangeValidator class
 * 
 * String length range validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class StringLengthRangeValidator extends NumericRangeValidator {
    
    /**
     * Create a new StringLengthRangeValidator object
     * @param integer|double $min The minimum value
     * @param integer|double $max The maximum value
     * @since 1.0-sofia
     */
    public function __construct($min, $max){
        parent::__construct($min, $max);
    }
    
    /**
     * Validate the value
     * @param string $value The value to validate
     * @return double Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function validate($value) {
        return parent::validate(strlen($value));
    }
    
}