<?php
namespace Packfire\Appliation\Pack;

/**
 * Theme class
 * 
 * Loader for application theme classes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Appliation\Pack
 * @since 1.1-sofia
 */
abstract class Theme {
    
    /**
     * Load a theme class from the template folder
     * @param string $theme The name of the theme class
     * @return pTheme Returns the loaded theme class
     * @since 1.1-sofia
     */
    public static function load($theme){
        $theme = ucfirst($theme) . 'Theme';
        return new $theme();
    }
    
}