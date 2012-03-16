<?php
pload('IValidator');

/**
 * Numerical range validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.0-sofia
 */
class pCheckboxValidator implements IValidator {
    
    /**
     * State of the check box
     * True is checked, False is unchecked
     * @var boolean
     * @since 1.0-sofia
     */
    private $state;
    
    /**
     * Create a new pCheckboxValidator
     * @param boolean $state State of the checkbox
     * @since 1.0-sofia
     */
    public function __construct($state){
        $this->state = $state;
    }
    
    /**
     * Validate the value
     * @param integer|double $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function filter($value) {
        return (bool)$value === $this->state;
    }
    
}