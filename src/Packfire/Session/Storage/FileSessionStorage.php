<?php
namespace Packfire\Session\Storage;

use SessionStorage;

/**
 * FileSessionStorage class
 * 
 * File storage for session
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.storage
 * @since 1.0-sofia
 */
class FileSessionStorage extends SessionStorage {
    
    /**
     * Path to the session storage location on the file system
     * @var string
     * @since 1.0-sofia
     */
    private $path;
    
    /**
     * Create a new pFileSessionStorage object
     * @param string $path Path to the storage location
     * @since 1.0-sofia
     */
    public function __construct($path = null){
        if($path){
            $this->path = $path;
        }else{
            $this->path = '';
        }
    }
    
    /**
     * Register the handler
     * @internal
     * @since 1.0-sofia 
     */
    protected function registerHandler() {
        ini_set('session.save_handler', 'files');
        ini_set('session.save_path', $this->path);
    }
    
}