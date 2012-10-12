<?php
namespace Packfire\Config\Driver;

use Packfire\Config\Config;
use Packfire\Yaml\Yaml;
use Packfire\IO\File\FileInputStream;

/**
 * YamlConfig class
 *
 * A YAML Configuration File
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config\Driver
 * @since 1.0-sofia
 */
class YamlConfig extends Config {

    /**
     * Read the configuration file
     * @since 1.0-sofia
     */
    protected function read() {
        $stream = new FileInputStream($this->file);
        $yaml = new Yaml($stream);
        $this->data = $yaml->read();
    }

}