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
    
    private $data;
    
    public function __construct($id = null){
        $this->id = $id;
        $this->data = new pMap();
    }
    
    public function id(){
        return $this->id;
    }
    
    public function clear() {
        $this->data->clear();
    }

    public function get($name, $default = null) {
        return $this->data->get($name, $default);
    }

    public function has($name) {
        return $this->data->keyExists($name);
    }

    public function load(&$data = null) {
        if($data){
            $this->data = new pMap($data);
        }
    }
    
    public function remove($name){
        $this->data->removeAt($name);
    }

    public function set($name, $value) {
        $this->data->add($name, $value);
    }
     
}