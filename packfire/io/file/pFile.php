<?php

class pFile {

    /**
     * Actual resolved pathname of the file
     * @var string
     */
    private $pathname;
    
    /**
     * Create a new pFile object
     * @param string $file Pathname of the file
     */
    public function __construct($file){
        $this->pathname = $file;
    }
    
    /**
     * Get the file size
     * @return integer
     */
    public function size(){
        return filesize($this->pathname);
    }

    /**
     * Create the file if file does not exists.
     */
    public function create(){
        if(!$this->exists()){
            $handle = fopen($this->pathname, 'w');
            fclose($handle);
        }
    }
    
    public function exists(){
        return is_file($this->pathname);
    }
    
    public function delete(){
        $ok = unlink($this->pathname);
        if(!$ok){
            //TODO Delete exception
        }
    }
    
    /**
     * Set the file content
     * @param string $content The content of the file
     */
    public function write($content){
        $ok = file_put_contents($this->pathname, $content);
        if(!$ok){
            // TODO write exception
        }
    }

    /**
     * Append the file content
     * @param string $ctn The additional file content to append
     * @return bool TRUE if successful, FALSE otherwise
     */
    public function append($ctn){
        $link = fopen($this->pathname, 'a');
        if($link){
            $ok = fwrite($link, $ctn);
            if(!$ok){
                // TODO write exception
            }
            @fclose($link);
        }else{
            // todo file open exception
        }
    }

    /**
     * Get the entire file content
     * @return string The entire file content is returned if successful.
     */
    public function read(){
        $content = file_get_contents($this->pathname);
        if($content === false){
            // TODO read exception
        }
        return $content;
    }
    
    public function copy($destination){
        if(pFileSystem::pathExists($destination)){
            $destination = pPath::combine($destination, pPath::baseName($this->pathname));
        }
        $ok = (bool)copy($this->pathname, $destination);
        if($ok){
            return new self($destination);
        }else{
            // TODO file copy exception
        }
    }

    /**
     * Get the entire pathname to the file
     * @return string
     */
    public function pathname(){
        return $this->pathname;
    }

    /**
     * Rename the file
     * @param string $newname The new name to give to the file
     * @return boolean TRUE if the rename is successful, FALSE otherwise.
     */
    public function rename($newname){
        $newname = pPath::path($this->pathname) . pPath::directorySeparator() . pPath::baseName($newname);
        $ok = rename($this->pathname, $newname);
        if($ok){    
            $this->pathname = $newname;
        }else{
            // TODO file rename fail exception
        }
    }

    /**
     * Move the file to another directory path
     * @param string $newdir The new directory path to move the file to
     * @return boolean TRUE if the move operation is successful, FALSE otherwise.
     */
    public function move($newdir){
        $newdir = $newdir . pPath::directorySeparator() . pPath::baseName($this->pathname);
        $ok = rename($this->pathname, $newdir);
        if($ok){    
            $this->pathname = $newdir;
        }else{
            // TODO file rename fail exception
        }
    }

    /**
     * Get or set the Last Modified attribute of the file
     * @param pDateTime $dt (optional) The datetime to set to
     * @return pDateTime The last modified timestamp of the file.
     */
    public function lastModified($dt = null){
        if(func_num_args() == 1){
            touch($this->pathname, $dt->toTimestamp());
            return $dt;
        }else{
            return pDateTime::fromTimestamp(filemtime($this->pathname));
        }
    }

    /**
     * Get the permission of the file
     * @return integer
     * @link http://php.net/chmod
     */
    public function permissions($p = null){
        if(func_num_args() == 1){
            $ok = chmod($this->pathname, $p);
            if(!$ok){
                // todo file permission change fail
            }
            return $p;
        }else{
            return fileperms($this->pathname);
        }
    }
    
    public function stream(){
        // TODO return stream
        // return new pFileStream($this->pathname);
    }
    
}