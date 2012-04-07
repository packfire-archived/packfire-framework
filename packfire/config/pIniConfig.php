<?php
pload('pConfig');

/**
 * An INI configuration file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pIniConfig extends pConfig {
    
    protected function read() {
        $this->data = parse_ini_file($this->file, true);
    }
    
}