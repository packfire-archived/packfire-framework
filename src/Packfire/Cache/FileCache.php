<?php
namespace Packfire\Cache;

use Packfire\Cache\ICache;
use Packfire\IO\File\File;
use Packfire\IO\File\Stream as FileStream;
use Packfire\IO\File\Path;
use Packfire\IO\File\System as FileSystem;
use Packfire\Data\Serialization\PhpSerializer;
use Packfire\DateTime\DateTime;
use Packfire\DateTime\TimeSpan;

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
     * The path to the storage location
     * @var string
     * @static
     * @since 1.0-sofia
     */
    private static $storePath;

    /**
     * Create a new FileCache object
     * @since 1.0-sofia
     */
    public function __construct(){
        if(!self::$storePath){
            self::$storePath = __APP_ROOT__ . 'storage/cache';
        }
    }

    /**
     * Create the path to the cache file identified by the identifier
     * @param string $id The identifier of the cache file
     * @return string Returns the path to the cache file
     * @since 1.0-sofia
     */
    private static function filePath($cacheId){
        return Path::combine(self::$storePath,
                'FileCache-' . self::idCleaner($cacheId) . '.cache');
    }

    /**
     * Cleans the ID into friendly for the file systems.
     * @param string $id The ID to be cleaned
     * @return string Returns the cleaned ID with lower case a-z, 0-9 and dash.
     * @since 1.0-sofia
     */
    private static function idCleaner($cacheId){
        return trim(preg_replace(array('`[^a-zA-Z0-9\-]+`', '`[\-]{1,}`'), '-', $cacheId), '-');
    }

    /**
     * Checks if a cache file is still fresh
     * @param string $file Path to the file to check
     * @return boolean Returns true if the cache file is fresh, false otherwise.
     * @since 1.0-sofia
     */
    private static function isCacheFresh($file){
        return (FileSystem::fileExists($file) && filemtime($file) >= time());
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
        $path = new Path(self::$storePath);
        $path->clear();
    }

    /**
     * Perform garbage collection to remove all expired and stale cache values
     * @since 1.0-sofia
     */
    public function garbageCollect() {
        $files = glob(self::$storePath .'/FileCache-*.cache', GLOB_NOSORT);
        if($files){
            $files = array_combine($files, array_map('filemtime', $files));
            asort($files);
            $time = time();
            foreach($files as $file => $expiry){
                if($expiry < $time){
                    @unlink($file);
                }else{
                    break;
                }
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
        if(FileSystem::fileExists($file)){
            $stream = new FileStream($file);
            $stream->open();
            $serializer = new PhpSerializer();
            $value = $serializer->deserialize($stream);
            $stream->close();
        }
        return $value;
    }

    /**
     * Store the cache value uniquely identified by the identifier with expiry
     * @param string $cacheId The identifier of the cache value
     * @param mixed $value The cache value to store
     * @param DateTime|TimeSpan $expiry (optional) The date time or period of 
     *          time to expire the cache value. If not set, the item will 
     *          never expire.
     * @since 1.0-sofia
     */
    public function set($cacheId, $value, $expiry = null) {
        $file = self::filePath($cacheId);
        if(!FileSystem::fileExists($file)){
            $fileTouch = new File($file);
            $fileTouch->create();
        }
        $stream = new FileStream($file);
        $stream->open();
        $serializer = new PhpSerializer();
        $value = $serializer->serialize($stream, $value);
        $stream->close();
        @chmod($file, 0755);
        
        if(func_num_args() == 3){
            if($expiry instanceof DateTime){
                $expiry = $expiry->toTimestamp();
            }else if($expiry instanceof TimeSpan){
                $expiry = time() + $expiry->totalSeconds();
            }else{
                $expiry = time() + 3600; // default to 1 hour cache?
            }
            touch($file, $expiry);
        }else{
            touch($file, PHP_INT_MAX);
        }
    }


}