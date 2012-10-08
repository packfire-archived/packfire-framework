<?php
namespace Packfire\Config;

use Packfire\Config\Config;

/**
 * PhpConfig class
 * 
 * A PHP configuration file that returns an array of configuration information.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config\Driver
 * @since 1.0-sofia
 */
class PhpConfig extends Config {    
    
    /**
     * Read the configuration file 
     * @since 1.0-sofia
     */
    protected function read() {
        $this->data = include($this->file);
    }
    
}