<?php
namespace Packfire\Config;

use Packfire\Config\ConfigType;
use Packfire\IO\File\Path;

/**
 * ConfigFactory class
 *
 * Factory class to create the appropriate Config class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
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
        if(array_key_exists($ext, $map)){
            $class = 'Packfire\\Config\\Driver\\' . $map[$ext];
            return new $class($file, $default);
        }else{
            return null;
        }
    }

}