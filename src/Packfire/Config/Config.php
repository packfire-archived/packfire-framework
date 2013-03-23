<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Config;

use Packfire\Config\IConfig;
use Packfire\Collection\ArrayHelper;

/**
 * A generic configuration file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Config
 * @since 1.0-sofia
 */
abstract class Config implements IConfig {
    
    /**
     * The pathname to the configuration file
     * @var string
     * @since 1.0-sofia
     */
    protected $file;
    
    /**
     * The data read from the configuration file
     * @var array
     * @since 1.0-sofia
     */
    protected $data;
    
    /**
     * Create a new configuration file
     * @param string $file Name of the configuration file to load
     * @since 1.0-sofia
     */
    public function __construct($file){
        $this->file = $file;
    }
    
    /**
     * Read the configuration file 
     * @since 1.0-sofia
     */
    public abstract function read();
    
    /**
     * Set the defaults for missing configuration
     * @param \Packfire\Config\Config $defaults The default configuration to place
     * @since 2.1.0
     */
    public function defaults(Config $defaults){
        $this->data = ArrayHelper::mergeRecursiveDistinct(
                $defaults->data, $this->data);
    }
    
    /**
     * Get the path to the file loaded
     * @return string Returns the path to the configuration file.
     * @since 1.0-sofia
     */
    public function file(){
        return $this->file;
    }
    
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
    public function get(){
        $keys = func_get_args();
        $data = $this->data;
        foreach($keys as $key){
            if(is_array($data)){
                if(isset($data[$key])){
                    $data = $data[$key];
                }else{
                    $data = null;
                    break;
                }
            }else{
                break;
            }
        }
        return $data;
    }
    
}