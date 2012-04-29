<?php

/**
 * ITemplate Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.template
 * @since 1.0-sofia
 */
interface ITemplate {
    
    public function __construct($template);
    
    public function parse();
    
    public function fields();
    
}