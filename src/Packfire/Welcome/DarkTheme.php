<?php
namespace Packfire\Welcome;

use Packfire\View\Theme;

/**
 * DarkTheme class
 * 
 * Provides rendering for dark theme
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Welcome
 * @since 1.0-sofia
 */
class DarkTheme extends Theme {
    
    public function render() {
        $this->define('style', 'dark');
    }
    
}