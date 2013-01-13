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

use Packfire\Collection\ArrayList;

/**
 * Provides generic functionality for finding classes in files
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
class ClassFinder implements IClassFinder {
    
    /**
     * The collection of namespace directories
     * @var array
     * @since 2.0.0
     */
    private $namespaces = array();
    
    /**
     * Additional Directories to search for
     * @var array
     * @since 2.0.0
     */
    private $searchDirs = array();
    
    /**
     * Assign a directory to load from for a namespace
     * @param string $namespace The namespace to be loaded
     * @param array|\Packfire\Collection\ArrayList $path The path(s) to look in for the namespace loading
     * @since 2.0.0
     */
    public function addNamespace($namespace, $path){
        if(isset($this->namespaces[$namespace])){
            if($path instanceof ArrayList){
                $path = $path->toArray();
            }
            $this->namespaces[$namespace] = 
                    array_merge($this->namespaces[$namespace], (array)$path);
        }else{
            $this->namespaces[$namespace] = (array)$path;
        }
    }
    
    /**
     * Add a generic search path
     * @param string $path The path to the source files
     * @since 2.0.0
     */
    public function addPath($path){
        $this->searchDirs[] = $path;
    }
    
    /**
     * Find the file pathname for a fully described class name
     * @param string $class Name of the class (preferably with the namespace too!)
     * @return string Returns the file pathname to the class
     * @since 2.0.0
     */
    public function find($class) {
        $class = ltrim($class, '\\');
        $fileName = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
        
        foreach($this->namespaces as $namespace => $dirs){
            if(0 === strpos($class, $namespace)){
                foreach($dirs as $dir){
                    $path = $dir . DIRECTORY_SEPARATOR . $fileName;
                    if(file_exists($path)){
                        return $path;
                    }
                }
            }
        }
        
        foreach($this->searchDirs as $dir){
            $path = $dir . DIRECTORY_SEPARATOR . $fileName;
            if(file_exists($path)){
                return $path;
            }
        }

        return stream_resolve_include_path($fileName);
    }
    
}