<?php
namespace Packfire\Core\ClassLoader;

/**
 * IClassLoader interface
 * 
 * Provides interface for class loading
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
interface IClassLoader {
    
    public function register();
    
    public function unregister();
    
    public function load($class);
    
}