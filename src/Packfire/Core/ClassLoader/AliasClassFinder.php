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
 * Provides alias rendering for class paths
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.1.1
 */
class AliasClassFinder
{
    /**
     * Class finder to work with
     * @var \Packfire\Core\ClassLoader\IClassFinder
     * @since 2.1.1
     */
    private $finder;

    /**
     * The collection of aliases
     * @var array
     * @since 2.1.1
     */
    private $aliases = array();

    /**
     * Create a new CacheClassFinder object
     * @param \Packfire\Core\ClassLoader\IClassFinder $finder The class finder to complement
     * @since 2.1.1
     */
    public function __construct($finder)
    {
        $this->finder = $finder;
    }

    /**
     * Register a namespace alias
     * @param string $alias  The alias to register
     * @param string $actual The actual namespace
     * @since 2.1.1
     */
    public function register($alias, $actual)
    {
        $this->aliases[$alias] = $actual;
    }

    /**
     * Remove a namespace alias
     * @param string $alias The alias to remove
     * @since 2.1.1
     */
    public function deregister($alias)
    {
        unset($this->aliases[$alias]);
    }

    /**
     * Find the file pathname for a fully described class name
     * @param  string $class Name of the class (preferably with the namespace too!)
     * @return string Returns the file pathname to the class
     * @since 2.1.1
     */
    public function find($class)
    {
        $class = str_replace(array_keys($this->aliases), $this->aliases, $class);
        return $this->finder->find($class);
    }
}
