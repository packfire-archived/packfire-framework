<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application\Pack;

/**
 * Theme class
 *
 * Loader for application theme classes
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Pack
 * @since 1.1-sofia
 */
abstract class Theme
{

    /**
     * Load a theme class from the theme folder
     * @param  string $theme The name of the theme class
     * @return Theme  Returns the loaded theme class
     * @since 1.1-sofia
     */
    public static function load($theme)
    {
        return new $theme();
    }
}
