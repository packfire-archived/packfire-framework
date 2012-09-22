<?php
pload('IValidator');

/**
 * pNumericRangeValidator
 * 
 * Numerical range validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.0-sofia
 */
class pNumericRangeValidator implements IValidator {
    
    /**
     * Minimum 
     * @var integer|double 
     * @since 1.0-sofia
     */
    private $min;
    
    /**
     * Maximum
     * @var integer|double 
     * @since 1.0-sofia
     */
    private $max;
    
    /**
     * Create a new numerical range validator pNumericRangeValidator
     * @param integer|double $min The minimum value
     * @param integer|double $max The maximum value
     * @since 1.0-sofia
     */
    public function __construct($min, $max){
        $this->min = $min;
        $this->max = $max;
    }
    
    /**
     * Validate the value
     * @param integer|double $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function validate($value) {
        return $this->min <= $value && $this->max >= $value;
    }
    
}