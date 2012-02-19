<?php

/**
 * File storage for session
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.storage
 * @since 1.0-sofia
 */
class pFileSessionStorage extends pSessionStorage {
    
    private $path;
    
    public function __construct($path = null){
        if($path){
            $this->path = $path;
        }else{
            $this->path = '';
        }
    }
    
    protected function registerHandler() {
        ini_set('session.save_handler', 'files');
        ini_set('session.save_path', $this->path);
    }
    
}