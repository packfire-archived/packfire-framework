<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Session\Storage;

/**
 * An interface for session handling
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Session\Storage
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