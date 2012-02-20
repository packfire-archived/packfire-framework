<?php
pload('pPathPart');

/**
 * Functionalities working with file system paths
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io.file
 * @since 1.0-sofia
 */
class pPath {
    
    /**
     * Path
     * @var string
     */
    private $path;
    
    /**
     * Create a new pPath object
     * @param type $path 
     */
    public function __construct($path){
        $this->path = $path;
    }
    
    /**
     * Create the directory path recursively.
     * @param integer $perm (optional) Permissions of the directory path.
     *                      Defaults to 0777
     * @return boolean TRUE if the creation was successful, FALSE otherwise.
     * @link http://php.net/mkdir
     * @since 1.0-sofia
     */
    public function create($perm = 0777){
        return (bool)mkdir($this->path, $perm, true);
    }

    /**
     * Remove the path
     * @return boolean TRUE if the directory is deleted, FALSE otherwise.
     * @link http://php.net/rmdir
     * @since 1.0-sofia
     */
    public function delete(){
        return (bool)rmdir($this->path);
    }

    /**
     * Empty the entire folder
     * 
     * Note that this method does not remove the folder itself, but clears
     * the content.
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
     * @return string The final combined path
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

        return str_replace('/', self::directorySeparator(), $path);
        }
    }

    /**
     * Get the absolute path to PHP's temp folder
     * @return string
     * @static
     */
    public static function tempPath(){
        return sys_get_temp_dir();
    }

    /**
     * Get only the file name from a path name
     * @param string $p The path name e.g. /home/user/public/test.html
     * @return string The file name e.g. test
     * @static
     * @see pPath::pathInfo()
     */
    public static function fileName($p){
        return self::pathInfo($p, pPathPart::FILENAME);
    }

    /**
     * Get the file name together with the file extension from a path name
     * @param string $p The path name e.g. /home/user/public/test.html
     * @return string The file name e.g. test.html
     * @static
     * @see pPath::pathInfo()
     */
    public static function baseName($p){
        return self::pathInfo($p, pPathPart::BASENAME);
    }

    /**
     * Get file extension from a path name
     * @param string $p The path name e.g. /home/user/public/test.html
     * @return string The file name e.g. html
     * @static
     * @see pPath::pathInfo()
     */
    public static function extension($p){
        return self::pathInfo($p, pPathPart::EXTENSION);
    }

    /**
     * Get only the directory path from a path name
     * @param string $p The path name e.g. /home/user/public/test.html
     * @return string The directory path e.g. /home/user/public/
     * @static
     * @see pPath::pathInfo()
     */
    public static function path($p){
        return self::pathInfo($p, pPathPart::DIRECTORY);
    }

    /**
     * Get information about a directory path
     * @param string $p The path name to get information abotu
     * @param string $a (optional) Particular information to retrieve. False to return all the information in an array
     * @return string|array
     * @link http://php.net/pathinfo
     * @static
     */
    public static function pathInfo($p, $a = false){
        if($a == pPathPart::FILENAME && version_compare(PHP_VERSION, '5.2', '<')){   
            // compatibility for "5.2.0 - The PATHINFO_FILENAME constant was added. "
            $basename =  self::baseName($p);
            $ext = self::extension($p);
            return substr($basename, 0, strlen($basename) - strlen($ext) - 1);
        }
        $k = pathinfo($p);
        if($a){
            return $k[$a];
        }
        return $k;
    }

    /**
     * Get the application's current working directory (usually from the application root)
     * @return string
     * @static
     */
    public static function currentWorkingPath(){
        return getcwd();
    }

    /**
     * Get the directory path of the current PHP script
     * @return string
     */
    public static function scriptPath(){
        return self::path($_SERVER['SCRIPT_FILENAME']);
    }

    /**
     * Resolves a directory path
     * @param string $p The directory path to resolve
     * @return string The resolved directory path
     * @static
     */
    public static function resolve($p){
        return realpath($p);
    }

    /**
     * Fetch the path to the file containing a specific class.
     * If the class is defined in the PHP core or PHP extension, NULL
     * will be returned
     * @param string $class Name of the class
     * @return string|null
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
