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

use Packfire\FuelBlade\IConsumer;

/**
 * Provides generic functionality for finding classes in files
 * through means of caches
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
class CacheClassFinder implements IClassFinder, IConsumer {
    
    /**
     * The cache driver
     * @var \Packfire\Cache\ICache
     * @since 2.1.0
     */
    private $cache;
    
    /**
     * Class finder to work with
     * @var \Packfire\Core\ClassLoader\IClassFinder
     * @since 2.1.0
     */
    private $finder;
    
    /**
     * The prefix to cache keys
     * @var string
     * @since 2.0.0
     */
    private $prefix;
    
    /**
     * Create a new CacheClassFinder object
     * @param \Packfire\Core\ClassLoader\IClassFinder $finder The class finder to complement
     * @param string $prefix (optional) A prefix to identify all class
     *              cache entries in the storage
     * @since 2.0.0
     */
    public function __construct($finder, $prefix = ''){
        $this->prefix = $prefix;
        $this->finder = $finder;
    }
    
    /**
     * Find the file pathname for a fully described class name
     * @param string $class Name of the class (preferably with the namespace too!)
     * @return string Returns the file pathname to the class
     * @since 2.0.0
     */
    public function find($class) {
        // normalize
        if(substr($class, 0, 1) == '\\'){
            $class = substr($class, 1);
        }
        $cacheId = $this->prefix . $class;
        if($this->cache->check($cacheId)){
            $file = $this->cache->get($cacheId);
        }else{
            $file = $this->finder->find($class);
            $this->cache->set($cacheId, $file);
        }
        return $file;
    }
    
    /**
     * Perform invoking of dependencies
     * @param \Packfire\FuelBlade\Container $c The IoC Container to inject dependencies
     * @return \Packfire\Core\ClassLoader\CacheClassFinder
     * @since 2.1.0
     */
    public function __invoke($c) {
        $this->cache = $c['cache'];
        return $this;
    }

}
