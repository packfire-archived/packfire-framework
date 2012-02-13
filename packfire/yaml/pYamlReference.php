<?php
Packfire::load('packfire.collection.pMap');

/**
 * Contains data of a reference map
 * You can access the data directly by $reference[$key] array access.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.yaml
 * @since 1.0-sofia
 */
class pYamlReference implements ArrayAccess {
    
    /**
     * The data of the reference
     * @var array
     */
    private $data;
    
    /**
     * Create a new reference
     * @param array $data The data
     */
    public function __construct($data){
        $this->data = $data;
    }
    
    public function __get($name){
        if(array_key_exists($name, $this->data)){
            return $this->data[$name];
        }
    }
    
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
    
    public function map(){
        return new pMap($this->data);
    }
    
    public function offsetExists($offset) {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset) {
        if(array_key_exists($offset, $this->data)){
            return $this->data[$offset];
        }
        return null;
    }

    public function offsetSet($offset, $value) {
        if($offset === null){
            $this->data[$offset] = $value;
        }else{
            $this->data[] = $value;
        }
    }

    public function offsetUnset($offset) {
        if(array_key_exists($offset, $this->data)){
            unset($this->data[$offset]);
        }
    }

}