<?php

/**
 * Session Storage interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.storage
 * @since 1.0-sofia
 */
interface ISessionStorage {
    
    public function start();
    
    public function read($key);
    
    public function write($key, $data);
    
    public function remove($key);
    
    public function id();
    
    public function destroy();
    
    public function regenerate();
    
}