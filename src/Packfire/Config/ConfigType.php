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

/**
 * Configuration file extensions and class mapping
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config
 * @since 1.0-sofia
 */
class ConfigType {
    
    /**
     * Provides the file type and class mapping information
     * @return array Returns the file type and class mapping information in array.
     * @since 1.0-sofia 
     */
    public static function typeMap(){
        static $map = array(
                'yml' => 'YamlConfig',
                'yaml' => 'YamlConfig',
                'ini' => 'IniConfig',
                'php' => 'PhpConfig'
            );
        return $map;
    }
    
}