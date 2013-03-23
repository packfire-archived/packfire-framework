<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Config\Driver;

use Packfire\Config\Config;

/**
 * An INI configuration file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config\Driver
 * @since 1.0-sofia
 */
class IniConfig extends Config {
    
    /**
     * Read the configuration file 
     * @since 1.0-sofia
     */
    public function read() {
        $this->data = parse_ini_file($this->file, true);
    }
    
}