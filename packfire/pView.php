<?php

/**
 * Description of View
 *
 * @author Sam Yong
 */
abstract class pView {
    
    private $fields;
    
    public function __construct(){
        $this->fields = new pList();
    }
    
    /**
     * Defines a template field to populate
     * @param string $key Name of the field
     * @param mixed $value (optional) Set the template field value
     * @return mixed If $value is not set, the current value set at $key is returned.
     */
    protected function define($key, $value = null){
        if(func_num_args() == 1){
            return $this->fields[$key];
        }else{
            $this->fields[$key] = $value;
        }
    }
    
    public function output(){
        
    }
    
}