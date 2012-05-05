<?php

/**
 * Configuration file extensions and class mapping
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pConfigType {
    
    public static function typeMap(){
        static $map = array(
                'yml' => 'pYamlConfig',
                'yaml' => 'pYamlConfig',
                'ini' => 'pIniConfig',
                'php' => 'pPhpConfig'
            );
        return $map;
    }
    
}