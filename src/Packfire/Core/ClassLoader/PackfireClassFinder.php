<?php
namespace Packfire\Core\ClassLoader;

/**
 * PackfireClassFinder class
 * 
 * Provides generic functionality for finding classes in Packfire
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
class PackfireClassFinder implements IClassFinder{
    
    /**
     * Find the file pathname for a fully described class name
     * @param string $class Name of the class (preferably with the namespace too!)
     * @return string Returns the file pathname to the class
     * @since 2.0.0
     */
    public function find($class) {
        $class = ltrim($class, '\\');
        if(0 === strpos($class, 'Packfire\\')){
            return realpath(__DIR__ . '/../../../' . $class . '.php');
        }
    }
    
}