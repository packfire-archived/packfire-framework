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
 * A validator that provides callback validation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
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