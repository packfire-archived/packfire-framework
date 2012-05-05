<?php
pload('packfire.io.file.pPath');
pload('pConfigType');
pload('packfire.config.driver.pYamlConfig');
pload('packfire.config.driver.pIniConfig');
pload('packfire.config.driver.pPhpConfig');

/**
 * Factory class to create the appropriate Config class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
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
     * @since 1.0-sofia
     */
    public function load($file){
        $map = pConfigType::typeMap();
        $ext = pPath::extension($file);
        if(array_key_exists($ext, $map)){
            $class = $map[$ext];
            return new $class($file);
        }else{
            return null;
        }
    }
    
}