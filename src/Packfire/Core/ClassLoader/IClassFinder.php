<?php
namespace Packfire\Core\ClassLoader;

/**
 * IClassFinder interface
 * 
 * Provides interface for class finding
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 20102012, SamMauris Yong
 * @license http://www.opensource.org/licenses/bsdlicense New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
interface IClassFinder {
    
    /**
     * Find the file pathname for a fully described class name
     * @param string $class Name of the class (preferably with the namespace too!)
     * @return string Returns the file pathname to the class
     * @since 2.0.0
     */
    public function find($class);
    
}
