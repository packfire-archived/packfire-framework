<?php

/**
 * Session abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session
 * @since 1.0-sofia
 */
interface ISession {
    
    public function __construct($storage);
    
    public function get($key);
    
    public function set($key, $value);
    
    public function clear();
    
    public function invalidate();
    
    public function regenerate();
    
}