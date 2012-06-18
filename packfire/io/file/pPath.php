<?php
pload('pPathPart');

/**
 * Functionalities working with file system paths
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io.file
 * @since 1.0-sofia
 */
class pPath {
    
    /**
     * The working path
     * @var string
     * @since 1.0-sofia
     */
    private $path;
    
    /**
     * Create a new pPath object
     * @param string $path The path to work with
     * @since 1.0-sofia
     */
    public function __construct($path){
        $this->path = $path;
    }
    
    /**
     * Create the directory path recursively.
     * @param integer $perm (optional) Permissions of the directory path.
     *                      Defaults to 0777
     * @return boolean Returns true if the creation was successful,
     *      false otherwise.
     * @link http://php.net/mkdir
     * @since 1.0-sofia
     */
    public function create($perm = 0777){
        return (bool)mkdir($this->path, $perm, true);
    }
    
    /**
     * Get the permission of the directory
     * @param integer $permission (optional) The permission to set the directory and its contents to
     * @return integer Returns the permission of the directory
     * @link http://php.net/chmod
     * @since 1.0-sofia
     */
    public function permission($permission = null){
        if(func_num_args() == 1){
            self::setPermission($this->path, $permission);
            return $permission;
        }else{
            return fileperms($this->path);
        }
    }
    
    /**
     * Set permission for a path recursively
     * @param string $path The path to set permission
     * @param integer $permission The permission to set the path and contents to
     * @since 1.0-sofia
     */
    private static function setPermission($path, $permission) {
        static $ignore = array('cgi-bin', '.', '..');
        $dir = @opendir($path);
        while (false !== ($file = readdir($dir))) {
            if (!in_array($file, $ignore)) {
                $file = $path . DIRECTORY_SEPARATOR . $file;
                chmod($file, $permission);
                if (is_dir($file)) {
                    self::setPermission($file, $permission);
                } 
            }
        }
        closedir($dir);
    }

    /**
     * Remove the path
     * @return boolean Returns true if the directory is deleted, false otherwise.
     * @link http://php.net/rmdir
     * @since 1.0-sofia
     */
    public function delete(){
        return (bool)rmdir($this->path);
    }
    
    /**
     * Copy a path and its contents to another
     * @param string $source The source path to copy from
     * @param string $destination The destination path to copy to
     * @since 1.0-sofia
     */
    public static function copy($source, $destination){
        if($source == $destination){
            return;
        }
        $dir = opendir($source); 
        mkdir($destination, 0777, true); 
        while(false !== ( $file = readdir($dir))) { 
            if(($file != '.') && ($file != '..')){ 
                if(is_dir($source . DIRECTORY_SEPARATOR . $file)){ 
                    self::copy($source . DIRECTORY_SEPARATOR . $file,
                            $destination . DIRECTORY_SEPARATOR . $file); 
                }else{
                    copy($source . DIRECTORY_SEPARATOR . $file,
                            $destination . DIRECTORY_SEPARATOR . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }

    /**
     * Empty the entire folder
     * 
     * Note that this method does not remove the folder itself, but clears its
     * own content.
     * 
     * @since 1.0-sofia
     */
    public function clear(){
        $dir = dir($this->path);
        while (($entry = $dir->read()) !== false) {
            $tp = self::combine($dir->path, '/' . $entry);
            $isdir = is_dir($tp);
            if($entry != '.' && $entry != '..'){
                if($isdir){
                    self::clear($tp);
                    rmdir($tp);
                }else{
                    unlink($tp);
                }
            }
        }
        $dir->close();
    }

    /**
     * Combine two paths into an elaborated path
     * @param string $path If left empty, the path will be worked from thE
     *                     current working path. See pPath::currentWorkingPath()
     * @param string $relative,... The relative path that will navigate from
     *                             $path. e.g. '../../test/example/run.html'
     *                             More relative paths can be appended.
     * @return string Returns the final combined path
     * @since 1.0-sofia
     */
    public static function combine($path, $relative){
        if(func_num_args() > 2){
            $params = func_get_args();
            $path = array_shift($params);
            foreach($params as $rp){
                $path = self::combine($path, $rp);
            }
            return $path;
        }else{
            if(!$relative){
                return $path;
            }
            $relative = str_replace(array('\\', '\\\\', '//'), '/', trim($relative));
            $path = str_replace(array('\\', '\\\\', '//'), '/', trim($path));
            if($path == ''){
                $path = self::currentWorkingPath();
            }
            if(substr($path, -1, 1) == '/'){
                $path = substr($path, 0, strlen($path)-1);
            }
            $relativeParts = explode('/', $relative);
            foreach($relativeParts as $p){
                if($p == ''){

                }elseif($p == '..'){
                    $path = self::path($path);
                }elseif($p != '.'){
                    $path .= '/' . $p;
                }
            }
            return str_replace('/', DIRECTORY_SEPARATOR, $path);
        }
    }

    /**
     * Get the absolute path to system's temporary directory
     * @return string Returns the system temporary directory
     * @since 1.0-sofia
     */
    public static function tempPath(){
        return sys_get_temp_dir();
    }

    /**
     * Get only the file name from a path name
     * @param string $p The path name e.g. /home/user/public/test.html
     * @return string Returns the file name e.g. 'test'
     * @see pPath::pathInfo()
     */
    public static function fileName($p){
        return self::pathInfo($p, pPathPart::FILENAME);
    }

    /**
     * Get the file name together with the file extension from a path name
     * @param string $p The path name e.g. /home/user/public/test.html
     * @return string Returns the file name e.g. 'test.html'
     * @see pPath::pathInfo()
     */
    public static function baseName($p){
        return self::pathInfo($p, pPathPart::BASENAME);
    }

    /**
     * Get file extension from a path name
     * @param string $p The path name e.g. /home/user/public/test.html
     * @return string Returns the file extension e.g. 'html'
     * @see pPath::pathInfo()
     */
    public static function extension($p){
        return self::pathInfo($p, pPathPart::EXTENSION);
    }

    /**
     * Get only the directory path from a path name
     * @param string $p The path name e.g. /home/user/public/test.html
     * @return string Returns the directory path e.g. 'home/user/public'
     * @see pPath::pathInfo()
     */
    public static function path($p){
        return self::pathInfo($p, pPathPart::DIRECTORY);
    }

    /**
     * Get information about a directory path
     * @param string $path The path name to get information about
     * @param string $info (optional) Particular information to retrieve. 
     *          False to return all the information in an array.
     * @return string|array Returns the path information
     * @link http://php.net/pathinfo
     * @since 1.0-sofia
     */
    public static function pathInfo($path, $info = false){
        if($info == pPathPart::FILENAME && version_compare(PHP_VERSION, '5.2', '<')){   
            // compatibility for "5.2.0 - The PATHINFO_FILENAME constant was added. "
            $basename =  self::baseName($path);
            $ext = self::extension($path);
            return substr($basename, 0, strlen($basename) - strlen($ext) - 1);
        }
        $result = pathinfo($path);
        if($info){
            $result = $result[$info];
        }
        return $result;
    }

    /**
     * Get the application's current working directory
     *          (usually from the application root)
     * @return string Returns the application current working path
     * @since 1.0-sofia
     */
    public static function currentWorkingPath(){
        return getcwd();
    }

    /**
     * Get the directory path of the current PHP script
     * @return string Returns the current script path
     * @since 1.0-sofia
     */
    public static function scriptPath(){
        return self::path($_SERVER['SCRIPT_FILENAME']);
    }

    /**
     * Resolves a directory path
     * @param string $p The directory path to resolve
     * @return string The resolved directory path
     * @since 1.0-sofia
     */
    public static function resolve($p){
        return realpath($p);
    }

    /**
     * Fetch the path to the file containing a specific class.
     * If the class is defined in the PHP core or PHP extension, null
     * will be returned instead.
     * @param string $class Name of the class
     * @return string Returns the path to the file of a class or null 
     *          if not found.
     * @since 1.0-sofia
     */
    public static function classPathName($class){
        $r = new ReflectionClass($class);
        $c = $r->getFileName();
        if($c){
            return $c;
        }
        return null;
    }

}
