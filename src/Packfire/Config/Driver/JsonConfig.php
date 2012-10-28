<?php
namespace Packfire\Config\Driver;

use Packfire\Config\Config;

/**
 * JsonConfig class
 *
 * A JSON configuration file that returns an array of configuration information.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config\Driver
 * @since 2.0.2
 */
class JsonConfig extends Config {

    /**
     * Read the configuration file
     * @since 2.0.2
     */
    protected function read() {
        var_dump(json_decode(file_get_contents($this->file), true));
        $this->data = json_decode(file_get_contents($this->file), true);
    }

}