<?php
pload('packfire.io.file.pPath');
pload('pYamlConfig');
pload('pIniConfig');

/**
 * Factory class to create the appropriate Config class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pConfigFactory {
    
    /**
     * Load a configuration file
     * @param string $file Path to the configuration file
     * @return pConfig Returns the loaded configuration, or NULL if failed to
     *                 find the appropriate configuration parser.
     */
    public function load($file){
        $map = array(
            'yml' => 'pYamlConfig',
            'yaml' => 'pYamlConfig',
            'ini' => 'pIniConfig',
        );
        $ext = pPath::extension($file);
        if(array_key_exists($ext, $map)){
            $class = $map[$ext];
            return new $class($file);
        }else{
            return null;
        }
    }
    
}