<?php
pload('IValidator');

/**
 * Match Validator
 * checks if value matches values provided
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.0-sofia
 */
class pMatchValidator implements IValidator {
    
    /**
     * The matches to validate against
     * @var array|IList|mixed
     * @since 1.0-sofia
     */
    private $matches;
    
    /**
     * Create a new pMatchValidator
     * @param array|IList|mixed $matches An array of values or the value to match against
     * @since 1.0-sofia
     */
    public function __construct($matches){
        $this->matches = $matches;
    }
    
    /**
     * Validate the value
     * @param mixed $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function filter($value) {
        if(is_array($this->matches) || $this->matches instanceof IList){
            return in_array($value, $this->matches);
        }else{
            return $this->matches == $value;
        }
    }
    
}