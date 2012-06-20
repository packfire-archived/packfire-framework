<?php

/**
 * A generic configuration storage
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
abstract class pConfig {
    
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
     * @param pConfig $default (optional) The default configuration data to load with.
     * @since 1.0-sofia
     */
    public function __construct($file, $default = null){
        $this->file = $file;
        $this->read();
        if($default){
            $this->data = array_merge($default->data, $this->data);
        }
    }
    
    /**
     * Read the configuration file 
     * @since 1.0-sofia
     */
    protected abstract function read();
    
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
                if(array_key_exists($key, $data)){
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