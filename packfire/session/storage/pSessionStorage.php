<?php

/**
 * Abstract session storage
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since 1.0-sofia
 * @todo to be implemented
 */
abstract class pSessionStorage implements ISessionStorage {
    
    public function __construct(){
        
    }
    
    public function destroy() {
        
    }

    public function id() {
        
    }

    public function read($key) {
        
    }

    public function remove($key) {
        
    }

    public function start() {
        
    }

    public function write($key, $data) {
        
    }
    
    public function regenerate() {
        
    }
    
    protected function registerHandler(){
        if($this instanceof ISessionHandler || $this instanceof SessionHandlerInterface){
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
    
}