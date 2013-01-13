<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Validator;

use Packfire\Validator\IValidator;

/**
 * Validation for data type
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class DataTypeValidator implements IValidator {
    
    /**
     * The data type to validate against
     * @var string 
     * @since 1.0-sofia
     */
    private $type;
    
    /**
     * Create a new DataTypeValidator class
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
            $result = ($this->type == get_class($value));
        }
        return $result;
    }
    
}
