<?php
namespace Packfire\Cache;

use ICache;
pload('packfire.io.file.pFile');
pload('packfire.io.file.pPath');
pload('packfire.io.file.pFileSystem');
pload('packfire.data.serialization.pPhpSerializer');

/**
 * FileCache class
 * 
 * Provides caching functionality to the file system
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Cache
 * @since 1.0-sofia
 */
class FileCache implements ICache {
    
    /**
     * The path to the storage location for pFileCache
     * @var string
     * @static
     * @since 1.0-sofia
     */
    private static $storePath;
    
    /**
     * Create a new pFileCache object
     * @since 1.0-sofia 
     */
    public function __construct(){
        if(!self::$storePath){
            self::$storePath = pPath::combine(__APP_ROOT__,
                    '/pack/storage/cache');
        }
    }
    
    /**
     * Create the path to the cache file identified by the identifier
     * @param string $id The identifier of the cache file
     * @return string Returns the path to the cache file
     * @since 1.0-sofia
     */
    private static function filePath($cacheId){
        return pPath::combine(self::$storePath,
                __CLASS__ . '-' . self::idCleaner($cacheId) . '.cache');
    }
    
    /**
     * Cleans the ID into friendly for the file systems.
     * @param string $id The ID to be cleaned
     * @return string Returns the cleaned ID with lower case a-z, 0-9 and dash.
     * @since 1.0-sofia
     */
    private static function idCleaner($cacheId){
        return strtolower(trim(preg_replace(array('`[^a-z0-9\-]+`', '`[\-]{1,}`'), '-', $cacheId), '-'));
    }
    
    /**
     * Checks if a cache file is still fresh
     * @param string $file Path to the file to check
     * @return boolean Returns true if the cache file is fresh, false otherwise.
     * @since 1.0-sofia
     */
    private static function isCacheFresh($file){
        return (pFileSystem::fileExists($file) && filemtime($file) >= time());
    }
    
    /**
     * Check if a cache value identified by the identifier is still fresh,
     *      available and has yet to expire. 
     * @param string $cacheId The identifier of the cache value
     * @return boolean Returns true if the cache value is fresh, available and
     *          has yet to expire. Returns false otherwise.
     * @since 1.0-sofia
     */
    public function check($cacheId) {
        $file = self::filePath($cacheId);
        return self::isCacheFresh($file);
    }

    /**
     * Remove the cache value identified by the identifier
     * @param string $cacheId The identifier of the cache value
     * @since 1.0-sofia
     */
    public function clear($cacheId) {
        $file = self::filePath($cacheId);
        @unlink($file);
    }

    /**
     * Remove all cache values regardless of their state.
     * @since 1.0-sofia 
     */
    public function flush() {
        $path = new pPath(self::$storePath);
        $path->clear();
    }

    /**
     * Perform garbage collection to remove all expired and stale cache values 
     * @since 1.0-sofia
     */
    public function garbageCollect() {
        $files = glob(self::$storePath . __CLASS__ . '-*.cache', GLOB_NOSORT);
        $cFiles = array_combine($files, array_map('filemtime', $files));
        asort($cFiles);
        $time = $time();
        foreach($cFiles as $file => $expiry){
            if($expiry < $time){
                @unlink($file);
            }else{
                break;
            }
        }
    }

    /**
     * Retrieve the fresh cache value identified by the identifier if the
     *          cache is fresh, available and yet to expire.
     * @param string $cacheId The identifier of the cache value
     * @param mixed $default (optional) The default value to return if the cache
     *          is stale, unavailable or expired. Defaults to null.
     * @return mixed Returns the fresh cache value or default value.
     * @since 1.0-sofia
     */
    public function get($cacheId, $default = null) {
        $file = self::filePath($cacheId);
        $value = $default;
        if(self::isCacheFresh($file)){
            $stream = new pFileStream($file);
            $stream->open();
            $serializer = new pPhpSerializer();
            $value = $serializer->deserialize($stream);
            $stream->close();
        }
        return $value;
    }

    /**
     * Store the cache value uniquely identified by the identifier with expiry
     * @param string $cacheId The identifier of the cache value
     * @param mixed $value The cache value to store
     * @param pDateTime|pTimeSpan $expiry The date time or period of time to 
     *              expire the cache value.
     * @since 1.0-sofia
     */
    public function set($cacheId, $value, $expiry) {
        if($expiry instanceof pDateTime){
            $expiry = $expiry->toTimestamp();
        }else if($expiry instanceof pTimeSpan){
            $expiry = time() + $expiry->totalSeconds();
        }else{
            $expiry = time() + 3600; // default to 1 hour cache?
        }
        $file = self::filePath($cacheId);
        $fileTouch = new pFile($file);
        $fileTouch->create();
        $stream = new pFileStream($file);
        $stream->open();
        $serializer = new pPhpSerializer();
        $value = $serializer->serialize($stream, $value);
        $stream->close();
        @chmod($file, 0755);
        touch($file, $expiry);
    }

    
}