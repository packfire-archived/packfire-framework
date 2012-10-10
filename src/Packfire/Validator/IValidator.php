<?php
namespace Packfire\Validator;

/**
 * IValidator interface
 * 
 * Validator abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
interface IValidator {
    
    public function validate($value);
    
}