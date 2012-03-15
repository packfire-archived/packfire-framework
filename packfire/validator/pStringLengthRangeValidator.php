<?php
pload('IValidator');
pload('pNumericRangeValidator');

/**
 * Numerical range validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.0-sofia
 */
class pStringLengthRangeValidator extends pNumericRangeValidator implements IValidator {
    
    /**
     * Create a new numerical range validator pNumericRangeValidator
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