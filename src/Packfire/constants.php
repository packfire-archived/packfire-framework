<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

/**
 * Constants for Packfire Framework
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @since 2.0.0
 */

/**
 * The root folder of the application front controller
 * @since 1.0-sofia
 */
if(!defined('__APP_ROOT__')){
    define('__APP_ROOT__', dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . 'pack' . DIRECTORY_SEPARATOR);
}

/**
 * Packfire Framework's current version
 * @since 1.0-sofia 
 */
define('__PACKFIRE_VERSION__', '2.0.7');
