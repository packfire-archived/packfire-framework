<?php
pload('packfire.collection.pMap');

/**
 * A theme abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.view
 * @since 1.0-sofia
 */
abstract class pTheme {
   
    /**
     * The fields in the theme settings defined
     * @var Map
     * @since 1.0-sofia
     */
    private $fields;
    
    /**
     * Create a new pTheme object
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->fields = new Map();
    }
    
    /**
     * Get the theme settings defined
     * @return Map Returns a map of theme settings
     * @since 1.0-sofia
     */
    public function fields(){
        return $this->fields;
    }
    
    /**
     * Define a theme setting
     * @param string|array|Map $key The name of the theme setting.
     * @param mixed $value (optional) The value of the theme setting.
     * @since 1.0-sofia 
     */
    protected function define($key, $value = null){
        if(func_num_args() == 1){
            if(is_string($key)){
                return $this->fields[$key];
            }else{
                $this->fields->append($key);
            }
        }else{
            $this->fields[$key] = $value;
        }
    }
    
    /**
     * Prepare and render the theme settings
     * @since 1.0-sofia
     */
    public abstract function render();
    
}