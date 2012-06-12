<?php
pload('ICache');
pload('packfire.io.file.pFile');
pload('packfire.io.file.pPath');
pload('packfire.io.file.pFileSystem');
pload('packfire.data.serialization.pPhpSerializer');

/**
 * pFileCache class
 * 
 * Provides caching functionality to the file system
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.cache
 * @since 1.0-sofia
 */
class pFileCache implements ICache {
    
    /**
     * The path to the storage location for pFileCache
     * @var string
     * @static
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
    private static function filePath($id){
        return pPath::combine(self::$storePath,
                __CLASS__ . '-' . hash('sha1', __CLASS__ . $id) . '.cache');
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
    
    public function check($id) {
        $file = self::filePath($id);
        return self::isCacheFresh($file);
    }

    public function clear($id) {
        $file = self::filePath($id);
        @unlink($file);
    }

    public function flush() {
        $path = new pPath(self::$storePath);
        $path->clear();
    }

    public function garbageCollect() {
        $files = glob(self::$storePath . __CLASS__ . '-*.cache', GLOB_NOSORT);
        $files = array_combine($files, array_map('filemtime', $files));
        asort($files);
        $time = $time();
        foreach($files as $file => $expiry){
            if($expiry < $time){
                @unlink($file);
            }else{
                break;
            }
        }
    }

    public function get($id, $default = null) {
        $file = self::filePath($id);
        $value = $default;
        if(self::isCacheFresh($file)){
            $stream = new pFileStream($file);
            $stream->open();
            $value = pPhpSerializer::deserialize($stream);
            $stream->close();
        }
        return $value;
    }

    public function set($id, $value, $expiry) {
        if($expiry instanceof pDateTime){
            $expiry = $expiry->toTimestamp();
        }else if($expiry instanceof pTimeSpan){
            $expiry = time() + $expiry->totalSeconds();
        }else{
            $expiry = time() + 3600; // default to 1 hour cache?
        }
        $file = self::filePath($id);
        $fileTouch = new pFile($file);
        $fileTouch->create();
        $stream = new pFileStream($file);
        $stream->open();
        $value = pPhpSerializer::serialize($stream, $value);
        $stream->close();
        chmod($file, 0777);
        touch($file, $expiry);
    }

    
}