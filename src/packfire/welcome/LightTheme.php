<?php
namespace Packfire\Welcome;

use Packfire\View\Theme;

/**
 * LightTheme class
 * 
 * Provides rendering for light theme
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.welcome
 * @since 1.0-sofia
 */
class LightTheme extends Theme {
    
    public function render() {
        $this->define('style', 'light');
    }
    
}