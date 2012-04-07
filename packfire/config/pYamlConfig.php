<?php
pload('pConfig');
pload('packfire.yaml.pYaml');
pload('packfire.io.file.pFileStream');

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
    
    protected function read() {
        $stream = new pFileStream($this->file);
        $yaml = new pYaml($stream);
        $this->data = $yaml->read(); 
    }
    
}