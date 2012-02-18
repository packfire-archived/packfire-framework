<?php
pload('pPathPart');

/**
 * pPath class
 * for functionalities related to paths
 *
 */
class pPath {
    
    /**
     * Create a directory path recursively
     * @param string $f The directory path to create
     * @param integer $perm (optional) Permissions of the directory path. Defaults to 0777
     * @return boolean TRUE if the creation was successful, FALSE otherwise.
     * @link http://php.net/mkdir
     * @static
     */
    public static function create($f, $perm = 0777){
        return (bool)mkdir($f, $perm, true);
    }

    /**
     * Remove a particular directory
     * @param string $f The directory to delete
     * @return boolean TRUE if the directory is deleted, FALSE otherwise.
     * @link http://php.net/rmdir
     * @static
     */
    public static function delete($f){
        return (bool)rmdir($f);
    }

    /**
     * Empty the entire folder
     * @param string $p Directory path to empty its content
     * @static
     */
    public static function clear($p){
        $d = dir($p);
        while (($entry = $d->read()) !== false) {
            $tp = self::combine($d->path, '/' . $entry);
            $isdir = is_dir($tp);
            if($entry == '.' || $entry == '..'){
                
            }else{
                if($isdir){
                    self::clear($tp);
                    rmdir($tp);
                }else{
                    unlink($tp);
                }
            }
        }
        $d->close();
    }

    /**
     * Combine two paths into an elaborated path
     * @param string $path If left empty, the path will be worked from the current working path. See pPath::currentWorkingPath()
     * @param string $relative,... The relative path that will navigate from $path. e.g. '../../test/example/run.html' More relative paths can be appended
     * @return string The final combined path
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
     * Get the directory separator for the system
     * @return string
     * @static
     */
    public static function directorySeparator() {
        return DIRECTORY_SEPARATOR;
    }

    /**
     * Get the path separator for the system
     * e.g for Windows it's ';', for Linux it is ':'
     * The separator can be used to separate several paths, for example:
     * On Windows C:\windows\system32;C:\xampp\htdocs;C:\gcc\bin
     * or
     * On Linux /var/www/myfile:/tmp/myfile
     * @return string
     * @static
     */
    public static function pathSeparator() {
        return PATH_SEPARATOR;
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
     * Get Packfire's path to the core files
     * @return string
     * @static
     */
    public static function packfireCorePath(){
        return self::path(__FILE__);
    }

    /**
     * Get Packfire's framework path
     * @return string
     * @static
     */
    public static function packfirePath(){
        return self::resolve(self::path(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
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
