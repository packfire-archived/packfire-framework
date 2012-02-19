<?php

/**
 * Session service
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session
 * @since 1.0-sofia
 */
class pSession {
    
    /**
     * Session Storage
     * @var ISessionStorage
     */
    private $storage;
    
    public function __construct($storage){
        $this->storage = $storage;
        session_start();
        $this->storage->load();
    }
    
    public function get($key){
        return $this->storage->get($key);
    }
    
    public function set($key, $value){
        $this->storage->set($key, $value);
    }
    
    public function clear(){
        $this->storage->clear();
    }
    
    public function invalidate(){
        $this->storage->clear();
        $this->storage->regenerate(true);
    }
    
    public function bucket($bucket){
        return $this->storage->bucket($bucket);
    }
    
}