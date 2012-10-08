<?php
namespace Packfire\Session;

/**
 * ISession interface
 * 
 * Session abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
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