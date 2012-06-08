<?php
pload('ISession');

/**
 * Session service
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session
 * @since 1.0-sofia
 */
class pSession implements ISession {
    
    /**
     * Session Storage
     * @var ISessionStorage
     * @since 1.0-sofia
     */
    private $storage;
    
    /**
     * Create a new pSession object
     * @param ISessionStorage $storage The session storage object
     * @since 1.0-sofia
     */
    public function __construct($storage){
        $this->storage = $storage;
        session_start();
        $this->storage->load();
    }
    
    /**
     * Get the value in the session by key
     * @param string $key The key of the value to fetch
     * @return mixed Returns the value
     * @since 1.0-sofia
     */
    public function get($key){
        return $this->storage->get($key);
    }
    
    /**
     * Set a key and value to the session
     * @param string $key The key that uniquely identify the value
     * @param mixed $value The value
     * @since 1.0-sofia
     */
    public function set($key, $value){
        $this->storage->set($key, $value);
    }
    
    /**
     * Clear the session of all the values
     * @since 1.0-sofia 
     */
    public function clear(){
        $this->storage->clear();
    }
    
    /**
     * Invalidate the session
     * @since 1.0-sofia 
     */
    public function invalidate(){
        $this->storage->clear();
        $this->storage->regenerate(true);
    }
    
    /**
     * Regenerate a new Session ID
     * @since 1.0-sofia 
     */
    public function regenerate(){
        $this->storage->regenerate();
    }
    
    /**
     * Fetch a session bucket 
     * @param string $bucket Name of the bucket to fetch
     * @return pSessionBucket Returns the bucket
     * @since 1.0-sofia
     */
    public function bucket($bucket){
        return $this->storage->bucket($bucket);
    }
    
    /**
     * Register a session bucket 
     * @param pSessionBucket $bucket The session bucket to register
     * @since 1.0-sofia
     */
    public function register($bucket){
        return $this->storage->register($bucket);
    }
    
}