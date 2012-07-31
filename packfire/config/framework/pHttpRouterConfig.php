<?php
pload('pFrameworkConfig');

/**
 * pRouterConfig class
 * 
 * Router configuration parser
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config.framework
 * @since 1.0-sofia
 */
class pRouterConfig extends pFrameworkConfig {
    
    /**
     * Load the routing configuration file located the the config folder.
     * @param string $context (optional) The context from which we are loading
     *                        the configuration file. $context can be any string
     *                        value such as 'local', 'test' or 'live' to determine
     *                        what values are loaded.
     * @return pConfig Returns a pConfig that has read and parsed the configuration file,
     *                 or NULL if the file is not recognized or not found.
     * @since 1.0-sofia
     */
    public static function load($context = __ENVIRONMENT__) {
        return self::execute('routing', $context);
    }
    
}