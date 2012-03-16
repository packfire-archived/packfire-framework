<?php
pload('IValidator');

/**
 * Data type validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.0-sofia
 */
class pDataTypeValidator implements IValidator {
    
    /**
     * The data type to validate against
     * @var string 
     * @since 1.0-sofia
     */
    private $type;
    
    /**
     * Create a new data type validator pDataTypeValidator
     * @param string $type The type of the variable to check against
     * @since 1.0-sofia
     */
    public function __construct($type){
        $this->type = $type;
    }
    
    /**
     * Validate the value
     * @param mixed $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function validate($value) {
        $type = gettype($value);
        $result = ($type == $this->type);
        if($type == 'object' && !$result){
            $result = ($type == get_class($value));
        }
        return $result;
    }
    
}
