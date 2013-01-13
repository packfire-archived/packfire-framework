<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Welcome;

use Packfire\View\Theme;

/**
 * Provides rendering for dark theme
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Welcome
 * @since 1.0-sofia
 */
class DarkTheme extends Theme {
    
    public function render() {
        $this->define('style', 'dark');
    }
    
}