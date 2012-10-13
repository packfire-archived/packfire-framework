<?php
namespace Packfire\Core\ClassLoader;

use Packfire\IoC\BucketUser;

/**
 * CacheClassFinder class
 * 
 * Provides generic functionality for finding classes in files
 * through means of caches
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core\ClassLoader
 * @since 2.0.0
 */
class CacheClassFinder extends BucketUser implements IClassFinder {
    
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
     * Get the cache storage
     * @return \Packfire\Cache\ICache Returns the cache abstraction
     * @since 2.0.0
     */
    protected function cache(){
        return $this->service('cache');
    }
    
    /**
     * Get the class finder service
     * @return ClassFinder Returns the class finder
     * @since 2.0.0
     */
    protected function finder(){
        return $this->service('autoload.finder');
    }
    
    /**
     * Find the file pathname for a fully described class name
     * @param string $class Name of the class (preferably with the namespace too!)
     * @return string Returns the file pathname to the class
     * @since 2.0.0
     */
    public function find($class) {
        $cacheId = $this->prefix . $class;
        if(null === $file = $this->cache()->get($cacheId)){
            $this->cache()->set($cacheId, $file = $this->finder()->find($class));
        }
        return $file;
    }

}
