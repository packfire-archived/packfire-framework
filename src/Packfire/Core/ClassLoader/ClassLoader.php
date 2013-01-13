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
 * Provides generic functionality for auto-loading class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
class ClassLoader implements IClassLoader {
    
    /**
     * The class finder
     * @var Packfire\Core\ClassLoader\ClassFinder
     * @since 2.0.0
     */
    private $finder;
    
    /**
     * Create a new ClassLoader object
     * @param \Packfire\Core\ClassLoader\ClassFinder $finder The finder used
     *      to look for the classes' files.
     * @since 2.0.0
     */
    public function __construct($finder = null){
        if(!$finder){
            $finder = new ClassFinder();
        }
        $this->finder = $finder;
    }
    
    /**
     * Load a class
     * @param string $class The full class name to load
     * @since 2.0.0
     */
    public function load($class) {
        $file = $this->finder->find($class);
        if($file){
            require $file;
        }
    }

    /**
     * Register this class loader
     * @param boolean $prepend (optional) Set whether this autoloader will be
     *          prepended to the autoloader stack. Defaults to false.
     * @since 2.0.0
     */
    public function register($prepend = false) {
        spl_autoload_register(array($this, 'load'), true, $prepend);
    }

    /**
     * Unregister this class loader
     * @since 2.0.0
     */
    public function unregister() {
        spl_autoload_unregister(array($this, 'load'));
    }
    
}