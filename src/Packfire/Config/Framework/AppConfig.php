<?php
namespace Packfire\Config\Framework;

use Packfire\Config\Framework\FrameworkConfig;

/**
 * AppConfig class
 * 
 * Application configuration parser
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config\Framework
 * @since 1.0-sofia
 */
class AppConfig extends FrameworkConfig {
    
    /**
     * Load the application configuration file located the the config folder.
     * @param string $context (optional) The context from which we are loading
     *                        the configuration file. $context can be any string
     *                        value such as 'local', 'test' or 'live' to determine
     *                        what values are loaded.
     * @return Config Returns a Config that has read and parsed the configuration file,
     *                 or NULL if the file is not recognized or not found.
     * @since 1.0-sofia
     */
    public function load($context = __ENVIRONMENT__){
        return $this->loadConfig('app', $context);
    }
    
}