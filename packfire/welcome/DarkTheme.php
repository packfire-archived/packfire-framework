<?php
pload('packfire.view.pTheme');

/**
 * DarkTheme class
 * 
 * Provides rendering for dark theme
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.welcome
 * @since 1.0-sofia
 */
class DarkTheme extends pTheme {
    
    public function render() {
        $this->define('style', 'dark');
    }
    
}