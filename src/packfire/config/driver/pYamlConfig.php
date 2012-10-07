<?php
pload('packfire.config.pConfig');
pload('packfire.yaml.pYaml');
pload('packfire.io.file.pFileInputStream');

/**
 * A YAML Configuration File
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pYamlConfig extends pConfig {
    
    /**
     * Read the configuration file 
     * @since 1.0-sofia
     */
    protected function read() {
        $stream = new pFileInputStream($this->file);
        $yaml = new pYaml($stream);
        $this->data = $yaml->read(); 
    }
    
}