<?php

/**
 * Configuration file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
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
     * @param string $name Name of the configuration file to load
     * @since 1.0-sofia
     */
    public function __construct($file){
        $this->file = $file;
        $this->read();
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
     * 
     * @param string $key,... The key of the data to load. 
     * @return mixed Returns the data read or NULL if the key is not found.
     * @since 1.0-sofia
     */
    public function get(){
        $keys = func_get_args();
        $data = $this->data;
        foreach($keys as $key){
            if(array_key_exists($key, $data)){
                $data = $data[$key];
            }else{
                $data = null;
                break;
            }
        }
        return $data;
    }
    
}