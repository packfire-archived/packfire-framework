<?php
pload('ISessionBucket');

/**
 * Session Bucket default implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.bucket
 * @since 1.0-sofia
 */
class pSessionBucket implements ISessionBucket {
    
    /**
     * The identifier of the bucket
     * @var type 
     */
    private $id;
    
    /**
     *
     * @var array
     */
    private $data;
    
    public function __construct($id = null){
        $this->id = $id;
        $this->data = array();
    }
    
    public function id(){
        return $this->id;
    }
    
    public function clear() {
        $this->data = array();
    }

    public function get($name, $default = null) {
        if(array_key_exists($name, $this->data)){
            return $this->data[$name];
        }
        return $default;
    }

    public function has($name) {
        return array_key_exists($name, $this->data);
    }

    public function load(&$data = null) {
        $this->data = &$data;
    }
    
    public function remove($name){
        if($this->has($name)){
            unset($this->data[$name]);
        }
    }

    public function set($name, $value) {
        $this->data[$name] = $value;
    }
     
}