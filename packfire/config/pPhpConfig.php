<?php
pload('pConfig');

/**
 * A PHP configuration file that returns an array of configuration information.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pPhpConfig extends pConfig {    
    
    protected function read() {
        $this->data = include($this->file);
    }
    
}