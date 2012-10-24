<?php
namespace Packfire\Application\Pack;

/**
 * Theme class
 * 
 * Loader for application theme classes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Pack
 * @since 1.1-sofia
 */
abstract class Theme {
    
    /**
     * Load a theme class from the theme folder
     * @param string $theme The name of the theme class
     * @return Theme Returns the loaded theme class
     * @since 1.1-sofia
     */
    public static function load($theme){
        return new $theme();
    }
    
}