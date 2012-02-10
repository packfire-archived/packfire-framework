<?php
Packfire::load('IView');
Packfire::load('pTemplate');

/**
 * The generic view class.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
abstract class pView implements IView {
    
    /**
     * The fields in the view defined
     * @var pMap
     * @since 1.0-sofia
     */
    private $fields;
    
    /**
     * Create a new view 
     */
    public function __construct(){
        $this->fields = new pMap();
    }
    
    /**
     * Define a template field to populate.
     * @param string $key Name of the field
     * @param mixed $value (optional) Set the template field value
     * @return mixed Returns the current value set at $key if $value is not set.
     * @since 1.0-sofia
     */
    protected function define($key, $value = null){
        if(func_num_args() == 1){
            return $this->fields[$key];
        }else{
            $this->fields[$key] = $value;
        }
    }
    
    /**
     * Get the output of the view.
     * @return string Returns the output of this view.
     * @since 1.0-sofia
     */
    public function output(){
        
    }
    
}