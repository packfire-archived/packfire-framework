<?php
namespace Packfire\Config\Driver;

use Packfire\Config\Config;

/**
 * IniConfig class
 * 
 * An INI configuration file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config\Driver
 * @since 1.0-sofia
 */
class IniConfig extends Config {
    
    /**
     * Read the configuration file 
     * @since 1.0-sofia
     */
    protected function read() {
        $this->data = parse_ini_file($this->file, true);
    }
    
}