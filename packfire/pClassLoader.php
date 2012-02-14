<?php

/**
 * Loads and prepares other classes for use.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pClassLoader {
    
    public static function load($package){
        $files = array();
        if(strpos($package, '.') === false){
            $files = self::prepareCurrentDirectoryFiles($package);
        }else{
            $files = self::prepareDirectorySearch($package);
        }
        foreach($files as $f){
            include_once($f);
        }
    } 
    
    private static function prepareCurrentDirectoryFiles($package){
        // since there is not a single dot,
        // it means there is only a name. 
        // search the current namespace for the class
        $a = debug_backtrace();
        reset($a);
        next($a);
        next($a);
        $trace = current($a);
        $path = $trace['file'];
        $path = pathinfo($path, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR;
        $search = $path . $package . '.php';
        $files = glob($search, GLOB_NOSORT);
        return $files;
    }
    
    private static function prepareDirectorySearch($package){
        $packages = array_filter(explode('.', $package));
        $root = array_shift($packages);
        if($root == 'packfire'){
            $path = implode(DIRECTORY_SEPARATOR, $packages);
            $search = __PACKFIRE_ROOT__ . $path . '.php';
        }else{
            $path = ($root ? ($root . DIRECTORY_SEPARATOR) : '') . implode(DIRECTORY_SEPARATOR, $packages);
            $search = __APP_ROOT__ . $path . '.php';
        }
        $files = glob($search, GLOB_NOSORT);
        return $files;
    }
    
}