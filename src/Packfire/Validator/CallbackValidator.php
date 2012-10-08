<?php
pload('IValidator');

/**
 * pCallbackValidator class
 * 
 * A validator that provides callback validation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.1-sofia
 */
class pCallbackValidator implements IValidator {
    
    /**
     * The callback to run validation
     * @var callback|Closure
     * @since 1.1-sofia
     */
    private $callback;
    
    public function __construct($callback){
        $this->callback = $callback;
    }
    
    public function validate($value){
        return call_user_func($this->callback, $value);
    }
    
}