<?php
pload('packfire.controller.IControllerFilter');

/**
 * Validator abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.0-sofia
 */
interface IValidator {
    
    public function validate($value);
    
}