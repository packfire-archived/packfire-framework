<?php
namespace Packfire\Validator;

use Packfire\Validator\IValidator;

/**
 * NumericValidator class
 * 
 * Numeric value validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class NumericValidator implements IValidator {
    
    /**
     * Validate the value
     * @param mixed $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function validate($value){
        return is_numeric($value);
    }
    
}