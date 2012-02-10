<?php

/**
 * The generic object class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pObject {
    
    public function __clone() {
        $properties = get_object_vars($this);
        foreach($properties as $k => $v){
            if(is_object($v)){
                if($v instanceof self){
                    $this->$k = $v->replica();
                }else{
                    if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
                        $this->$k = clone $v;
                    }
                }
            }
        }
    }
    
    public function replica(){
        if (version_compare(PHP_VERSION, '5.0.0', '<')) {
            return $this;
        } else {
            return clone $this;
        }
    }
    
}