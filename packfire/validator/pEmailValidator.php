<?php
pload('pRegexValidator');

/**
 * Email address validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.validator
 * @since 1.0-sofia
 */
class pEmailValidator extends pRegexValidator {
    
    /**
     * Create a new pEmailValidator
     * @since 1.0-sofia
     */
    public function __construct(){
        parent::__construct('`^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$`is');
    }
    
}