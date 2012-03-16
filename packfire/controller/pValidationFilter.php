<?php
pload('IControllerFilter');
pload('packfire.exception.pValidationException');

class pValidationFilter implements IControllerFilter {
    
    /**
     * The validator
     * @var IValidator
     * @since 1.0-sofia
     */
    private $validator;
    
    public function __construct($validator){
        $this->validator = $validator;
    }
    
    public function filter($value) {
        if(!$this->validator->validate($value)){
            throw new pValidationException('Validation failed on value.', 1020);
        }
        return $value;
    }
    
}