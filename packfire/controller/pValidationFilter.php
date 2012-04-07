<?php
pload('IControllerFilter');
pload('packfire.exception.pValidationException');

/**
 * A filter for validators
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.controller
 * @since 1.0-sofia
 */
class pValidationFilter implements IControllerFilter {
    
    /**
     * The validator
     * @var IValidator
     * @since 1.0-sofia
     */
    private $validator;
    
    /**
     * Create a new pValidationFilter object
     * @param IValidator $validator The validator
     * @since 1.0-sofia
     */
    public function __construct($validator){
        $this->validator = $validator;
    }
    
    /**
     * Performs the filter operation
     * @param mixed $value The value to validate
     * @return mixed Returns the original value
     * @throws pValidationException Thrown when validation fails.
     * @since 1.0-sofia
     */
    public function filter($value) {
        if(!$this->validator->validate($value)){
            throw new pValidationException('Validation failed on value.', 1020);
        }
        return $value;
    }
    
}