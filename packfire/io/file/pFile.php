<?php
pload('packfire.exception.pIOException');

/**
 * A file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io.file
 * @since 1.0-sofia
 */
class pFile {

    /**
     * Actual resolved pathname of the file
     * @var string
     * @since 1.0-sofia
     */
    private $pathname;
    
    /**
     * Create a new pFile object
     * @param string $file Pathname of the file
     * @since 1.0-sofia
     */
    public function __construct($file){
        $this->pathname = $file;
    }
    
    /**
     * Get the file size
     * @return integer
     * @since 1.0-sofia
     */
    public function size(){
        return filesize($this->pathname);
    }

    /**
     * Create the file if file does not exists.
     * @since 1.0-sofia
     */
    public function create(){
        if(!$this->exists()){
            $handle = fopen($this->pathname, 'w');
            fclose($handle);
        }
    }
    
    /**
     * Tell if the file exists or not
     * @return boolean Returns true if the file exists, false otherwise.
     * @since 1.0-sofia
     */
    public function exists(){
        return is_file($this->pathname);
    }
    
    /**
     * Delete the file
     * @throws pIOException 
     * @since 1.0-sofia
     */
    public function delete(){
        $ok = unlink($this->pathname);
        if(!$ok){
            throw new pIOException(
                    sprintf('An error occurred deleting file \'%s\'.',
                            $this->pathname)
                );
        }
    }
    
    /**
     * Set the file content
     * @param string $content The content of the file
     * @throws pIOException
     * @since 1.0-sofia
     */
    public function write($content){
        $ok = file_put_contents($this->pathname, $content);
        if(!$ok){
            throw new pIOException(
                    sprintf('Failed to write content file \'%s\'.', $this->pathname)
                );
        }
    }

    /**
     * Append the file content
     * @param string $ctn The additional file content to append
     * @return bool Returns true if successful, false otherwise/
     * @throws pIOException
     * @since 1.0-sofia
     */
    public function append($ctn){
        $link = fopen($this->pathname, 'a');
        if($link){
            $ok = fwrite($link, $ctn);
            if(!$ok){
            throw new pIOException(
                    sprintf('An error occurred while appending '.
                            'content to file \'%s\'.', $this->pathname)
                );
            }
            @fclose($link);
        }else{
            throw new pIOException(
                    sprintf('Failed opening file \'%s\'.', $this->pathname)
                );
        }
    }

    /**
     * Get the entire file content
     * @return string The entire file content is returned if successful.
     * @throws pIOException
     * @since 1.0-sofia
     */
    public function read(){
        $content = file_get_contents($this->pathname);
        if($content === false){
            throw new pIOException(
                    sprintf('An error occurred reading file \'%s\'.',
                            $this->pathname)
                );
        }
        return $content;
    }
    
    /**
     * Copy the file to another destination
     * @param string $destination The destination path to copy to
     * @return pFile Returns the file object that maps to the new copy at the
     *               destination path.
     * @throws pIOException 
     * @since 1.0-sofia
     */
    public function copy($destination){
        if(pFileSystem::pathExists($destination)){
            $destination = pPath::combine($destination,
                    pPath::baseName($this->pathname));
        }
        $ok = (bool)copy($this->pathname, $destination);
        if($ok){
            return new self($destination);
        }else{
            throw new pIOException(
                    sprintf('Failed to copy file \'%s\' to destination \'%s\'.',
                            $this->pathname, $destination)
                );
        }
    }

    /**
     * Get the entire pathname to the file
     * @return string
     * @since 1.0-sofia
     */
    public function pathname(){
        return $this->pathname;
    }

    /**
     * Rename the file
     * @param string $newname The new name to give to the file
     * @return boolean Returns true if the rename is successful, false otherwise.
     * @throws pIOException
     * @since 1.0-sofia
     */
    public function rename($newname){
        $newname = pPath::path($this->pathname) . pPath::directorySeparator() 
                . pPath::baseName($newname);
        $ok = rename($this->pathname, $newname);
        if($ok){    
            $this->pathname = $newname;
        }else{
            throw new pIOException(
                    sprintf('An error occurred renaming file \'%s\' to \'%s\'.',
                            $this->pathname, $newname)
                );
        }
    }

    /**
     * Move the file to another directory path
     * @param string $newdir The new directory path to move the file to
     * @return boolean Returns true if the move operation is successful,
     *                 false otherwise.
     * @throws pIOException
     * @since 1.0-sofia
     */
    public function move($newdir){
        $newdir = $newdir . pPath::directorySeparator()
                . pPath::baseName($this->pathname);
        $ok = rename($this->pathname, $newdir);
        if($ok){    
            $this->pathname = $newdir;
        }else{
            throw new pIOException(
                    sprintf('An error occurred moving file \'%s\' to \'%s\'.',
                            $this->pathname, $newdir)
                );
        }
    }

    /**
     * Get or set the Last Modified attribute of the file
     * @param pDateTime $dt (optional) The datetime to set to
     * @return pDateTime The last modified timestamp of the file.
     * @since 1.0-sofia
     */
    public function lastModified($datetime = null){
        if(func_num_args() == 1){
            touch($this->pathname, $datetime->toTimestamp());
            return $datetime;
        }else{
            return pDateTime::fromTimestamp(filemtime($this->pathname));
        }
    }

    /**
     * Get the permission of the file
     * @return integer The permission to set to.
     * @link http://php.net/chmod
     * @since 1.0-sofia
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
    
    /**
     * Get the stream for this file
     * @return pFileStream Returns the stream to access this file
     * @since 1.0-sofia
     */
    public function stream(){
        return new pFileStream($this->pathname);
    }
    
}