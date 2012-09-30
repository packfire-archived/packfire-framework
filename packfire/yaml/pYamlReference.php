<?php
pload('packfire.collection.pMap');

/**
 * Contains data of a reference map
 * You can access the data directly by $reference[$key] array access.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.yaml
 * @since 1.0-sofia
 */
class pYamlReference implements ArrayAccess {
    
    /**
     * The data of the reference
     * @var array
     * @since 1.0-sofia
     */
    private $data;
    
    /**
     * Create a new reference
     * @param array $data The data
     * @since 1.0-sofia
     */
    public function __construct($data){
        $this->data = $data;
    }
    
    /**
     * Provide direct attribute get access to the data
     * @param string $name Name of the attribute
     * @return mixed Returns the attribute value or NULL if not found
     * @internal
     * @since 1.0-sofia
     */
    public function __get($name){
        if(array_key_exists($name, $this->data)){
            return $this->data[$name];
        }
    }
    
    /**
     * Provide direct attribute set access to the data
     * @param string $name Name of the attribute
     * @param mixed $value The value of the attribute to set
     * @internal
     * @since 1.0-sofia
     */
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
    
    /**
     * Get a pMap of the data
     * @return pMap Returns a pMap of the data
     * @since 1.0-sofia
     */
    public function map(){
        return new pMap($this->data);
    }
    
    /**
     * Array access, internal, implementation of ArrayAccess
     * @param mixed $offset
     * @return boolean 
     * @internal
     * @since 1.0-sofia
     */
    public function offsetExists($offset) {
        return array_key_exists($offset, $this->data);
    }
    
    /**
     * Array access, internal, implementation of ArrayAccess
     * @param mixed $offset
     * @return mixed 
     * @internal
     * @since 1.0-sofia
     */
    public function offsetGet($offset) {
        if(array_key_exists($offset, $this->data)){
            return $this->data[$offset];
        }
        return null;
    }
    
    /**
     * Array access, internal, implementation of ArrayAccess
     * @param mixed $offset
     * @param mixed $value
     * @internal
     * @since 1.0-sofia
     */
    public function offsetSet($offset, $value) {
        if($offset === null){
            $this->data[] = $value;
        }else{
            $this->data[$offset] = $value;
        }
    }
    
    /**
     * Array access, internal, implementation of ArrayAccess
     * @param mixed $offset
     * @internal
     * @since 1.0-sofia
     */
    public function offsetUnset($offset) {
        if(array_key_exists($offset, $this->data)){
            unset($this->data[$offset]);
        }
    }

}