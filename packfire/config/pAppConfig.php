<?php

/**
 * 
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pAppConfig {
    
    /**
     * Load an application configuration file located the the config folder.
     * @param string $name Name of the configuration file to load
     * @param string $context (optional) The context from which we are loading
     *                        the configuration file. $context can be any string
     *                        value such as 'local', 'test' or 'live' to determine
     *                        what values are loaded.
     * @return pConfig Returns a pConfig that has read and parsed the configuration file,
     *                 or NULL if the file is not recognized or not found.
     * @since 1.0-sofia
     */
    public static function load($name, $context = null){
        $path = __APP_ROOT__ . 'packfire/config/' . $name;
        
        // parsers
        $testFiles = array(
            'yml' => 'pYamlConfig',
            'yaml' => 'pYamlConfig',
            'ini' => 'pIniConfig',
        );
        
        if($context){
            foreach($testFiles as $type => $class){
                if(file_exists($path . '.' . $context . '.' . $type)){
                    return new $class($file);
                }
            }
        }
        // fall back if with context the file is not found
        foreach($testFiles as $type => $class){
            if(file_exists($path . '.' .  $type)){
                return new $class($file);
            }
        }
        
        return null;
    }
    
}