<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Core\ClassLoader;

/**
 * IClassLoader interface
 * 
 * Provides interface for class loading
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
interface IClassLoader {
    
    /**
     * Register this class loader
     * @param boolean $prepend (optional) Set whether this autoloader will be
     *          prepended to the autoloader stack. Defaults to false.
     * @since 2.0.0
     */
    public function register($prepend = false);
    
    /**
     * Unregister this class loader
     * @since 2.0.0
     */
    public function unregister();
    
    /**
     * Load a class
     * @param string $class The full class name to load
     * @since 2.0.0
     */
    public function load($class);
    
}