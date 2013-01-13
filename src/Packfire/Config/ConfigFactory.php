<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Config;

use Packfire\Config\ConfigType;
use Packfire\IO\File\Path;

/**
 * Factory class to create the appropriate Config class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config
 * @since 1.0-sofia
 */
class ConfigFactory {

    /**
     * Load a configuration file
     * @param string $file Path to the configuration file
     * @return Config Returns the loaded configuration, or NULL if failed to
     *                 find the appropriate configuration parser.
     * @since 1.0-sofia
     */
    public function load($file, $default = null){
        $map = ConfigType::typeMap();
        $ext = Path::extension($file);
        if(isset($map[$ext])){
            $class = 'Packfire\\Config\\Driver\\' . $map[$ext];
            return new $class($file, $default);
        }else{
            return null;
        }
    }

}