<?php

/**
 * Session Bucket Interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.bucket
 * @since 1.0-sofia
 */
interface ISessionBucket {
    
    public function id();
    
    public function load(&$data = null);
    
    public function has($name);
    
    public function get($name, $default = null);
    
    public function set($name, $value);
    
    public function remove($name);
    
    public function clear();
    
}