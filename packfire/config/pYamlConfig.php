<?php
pload('pConfig');
pload('packfire.yaml.pYaml');
pload('packfire.io.file.pFileStream');

/**
 * A YAML Configuration File
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
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