<?php

/**
 * IFrameworkConfig interface
 * 
 * Framework configuration file loader abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config.framework
 * @since 1.0-sofia
 */
interface IFrameworkConfig {
    
    public static function load($environment = __ENVIRONMENT__);
    
}