<?php
namespace Packfire\Validator;

use Packfire\Validator\RegexValidator;

/**
 * Email address validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class EmailValidator extends RegexValidator {
    
    /**
     * Create a new EmailValidator object
     * @since 1.0-sofia
     */
    public function __construct(){
        parent::__construct('`^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$`is');
    }
    
}