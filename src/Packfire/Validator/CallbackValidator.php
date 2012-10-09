<?php
namespace Packfire\Validator;

use IValidator;

/**
 * CallbackValidator class
 * 
 * A validator that provides callback validation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.1-sofia
 */
class CallbackValidator implements IValidator {
    
    /**
     * The callback to run validation
     * @var callback|Closure
     * @since 1.1-sofia
     */
    private $callback;
    
    /**
     * Create a new CallbackValidator object
     * @param Closure|callback $callback The callback to run the validation
     * @since 1.1-sofia
     */
    public function __construct($callback){
        $this->callback = $callback;
    }
    
    /**
     * Validate the value
     * @param mixed $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.1-sofia
     */
    public function validate($value){
        return call_user_func($this->callback, $value);
    }
    
}