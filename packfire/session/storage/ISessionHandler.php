<?php

/**
 * Session handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.storage
 * @since 1.0-sofia
 * @link http://php.net/manual/en/function.session-set-save-handler.php
 */
interface ISessionHandler {
    
    public function open($path, $sessionName);
    
    public function read($sessionId);
    
    public function write($sessionId, $data);
    
    public function destroy($sessionId);
    
    public function garbageCollect($life);
    
    public function close();
    
}