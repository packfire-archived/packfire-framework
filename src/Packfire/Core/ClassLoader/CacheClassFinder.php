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
    
    private $cache;
    
    private $finder;
    
    /**
     * The prefix to cache keys
     * @var string
     * @since 2.0.0
     */
    private $prefix;
    
    /**
     * Create a new CacheClassFinder object
     * @param \Packfire\Cache\ICache $cache The cache storage
     * @param string $prefix (optional) A prefix to identify all class
     *              cache entries in the storage
     * @since 2.0.0
     */
    public function __construct($prefix = ''){
        $this->prefix = $prefix;
    }
    
    /**
     * Find the file pathname for a fully described class name
     * @param string $class Name of the class (preferably with the namespace too!)
     * @return string Returns the file pathname to the class
     * @since 2.0.0
     */
    public function find($class) {
        $cacheId = $this->prefix . $class;
        if($this->cache->check($cacheId)){
            $file = $this->cache->get($cacheId);
        }else{
            $file = $this->finder->find($class);
            $this->cache->set($cacheId, $file);
        }
        return $file;
    }
    
    public function __invoke($c) {
        $this->cache = $c['cache'];
        $this->finder = $c['autoload.finder'];
    }

}
