<?php
pload('packfire.config.pConfigType');
pload('packfire.config.pConfigFactory');
pload('IFrameworkConfig');

/**
 * Framework Application configuration parser
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config.framework
 * @since 1.0-sofia
 */
abstract class pFrameworkConfig implements IFrameworkConfig {
    
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
    protected static function execute($name, $context){
        $path = __APP_ROOT__ . 'pack/config/' . $name;
        
        $map = array_keys(pConfigType::typeMap());
        
        $factory = new pConfigFactory();
        foreach($map as $type){
            if($context && is_file($path . '.' . $context . '.' . $type)){
                return $factory->load($path . '.' . $context . '.' . $type);
            }
            if(is_file($path . '.' . $type)){
                return $factory->load($path . '.' . $type);
            }
        }
        return null;
    }
    
}