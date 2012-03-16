<?php

/**
 * Controller Parameter Filter abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.controller
 * @since 1.0-sofia
 */
interface IControllerFilter {
    
    public function filter($value);
    
}