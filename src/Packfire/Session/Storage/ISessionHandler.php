<?php

/**
 * ISessionHandler interface
 * 
 * An interface for session handling
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
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