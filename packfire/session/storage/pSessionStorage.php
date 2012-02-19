<?php
pload('ISessionStorage');
pload('packfire.session.bucket.pSessionBucket');

/**
 * Abstract session storage
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since 1.0-sofia
 */
class pSessionStorage implements ISessionStorage {
    
    /**
     * The container of buckets
     * @var pMap
     * @since 1.0-sofia
     */
    private $buckets;
    
    /**
     * Flags whether the session has started or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $started;
    
    /**
     * Create a new pSessionStorage object
     * @since 1.0-sofia 
     */
    public function __construct(){
        $this->buckets = new pMap();
        $this->register(new pSessionBucket($this->id()));
        $this->started = false;
        $this->registerHandler();
        $this->registerShutdown();
    }

    public function id() {
        return 'packfireDefault';
    }

    public function get($key, $default = null) {
        return $this->bucket($this->id())->get($key, $default);
    }

    public function remove($key) {
        $this->bucket($this->id())->remove($key);
    }

    public function set($key, $data) {
        $this->bucket($this->id())->set($key, $data);
    }
    
    public function regenerate($delete = false) {
        session_regenerate_id($delete);
    }
    
    protected function registerHandler(){
        if($this instanceof ISessionHandler
                || $this instanceof SessionHandlerInterface){
            session_set_save_handler(
                array($this, 'open'),
                array($this, 'close'),
                array($this, 'read'),
                array($this, 'write'),
                array($this, 'destroy'),
                array($this, 'gc')
            );
        }
    }
    
    protected function registerShutdown(){
        register_shutdown_function('session_write_close');
    }
    
    /**
     *
     * @param ISessionBucket $bucket 
     */
    public function register($bucket) {
        $id = $bucket->id();
        if($id){
            $this->buckets[$id] = $bucket;
        }
    }
    
    public function bucket($id){
        return $this->buckets->get($id);
    }
    
    public function clear() {
        $this->bucket($this->id())->clear();
    }

    public function load(&$data = null) {
        if(func_num_args() == 0){
            $data = &$_SESSION;
        }
        
        foreach($this->buckets as $id => $bucket){
            if(!array_key_exists($id, $data)){
                $data[$id] = array();
            }
            $bucket->load($data[$id]);
        }
        
    }

    public function has($key){
        $this->bucket($this->id())->has($key);
    }
    
}