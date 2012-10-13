<?php
namespace Packfire\Core\ClassLoader;

/**
 * ClassFinder class
 * 
 * Provides generic functionality for finding classes in files
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
class ClassFinder {
    
    /**
     * The collection of namespace directories
     * @var array
     * @since 2.0.0
     */
    private $namespaces = array();
    
    /**
     * Assign a directory to load from for a namespace
     * @param string $namespace The namespace to be loaded
     * @param array|\Packfire\Core\ClassLoader\IList $path The path(s) to look in for the namespace loading
     * @since 2.0.0
     */
    public function addNamespace($namespace, $path){
        if(!isset($this->namespaces[$namespace])){
            $this->namespaces[$namespace] = array();
        }
        if(is_array($path) || $path instanceof Packfire\Collection\IList){
            foreach($path as $sPath){
                $this->namespaces[$namespace][] = $sPath;
            }
        }else{
            $this->namespaces[$namespace][] = $path;
        }
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

        return stream_resolve_include_path($fileName);
    }
    
}