<?php
pload('packfire.config.driver.pYamlConfig');
pload('packfire.config.driver.pIniConfig');
pload('packfire.config.driver.pPhpConfig');

/**
 * pConfigType class
 * 
 * Configuration file extensions and class mapping
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pConfigType {
    
    /**
     * Provides the file type and class mapping information
     * @return array Returns the file type and class mapping information in array.
     * @since 1.0-sofia 
     */
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