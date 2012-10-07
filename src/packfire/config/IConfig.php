<?php

/**
 * IConfig interface
 * 
 * Interfacing and abstraction for configurations
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
interface IConfig {
    
    /**
     * Get the value from the configuration file.
     * 
     * You can get values nested inside arrays by entering multiple keys as
     * arguments to the method.
     * 
     * Example:
     * <code>$value = $config->get('app', 'name'); // $data = array('app' => array('name' => 'Packfire')); </code>
     * <code>$value = $config->get('database', 'default', 'host'); // $data = array('database' => array('default' => array('host' => 'localhost'))); </code>
     * 
     * @param string $key,... The key of the data to load. 
     * @return mixed Returns the data read or NULL if the key is not found.
     * @since 1.0-sofia
     */
    public function get();
    
}