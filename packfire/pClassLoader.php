<?php
pload('packfire.collection.pList');

/**
 * Loads and prepares other classes for use.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pClassLoader {
    
    /**
     * PHP file extension 
     * @since 1.0-sofia
     */
    const EXT = '.php';
    
    /**
     * The list of loaded classes
     * @var array
     * @since 1.0-sofia
     */
    private $loadedClasses;
    
    /**
     * Create a new pClassLoader 
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->loadedClasses = array();
    }
    
    /**
     * Get the list of loaded classes
     * @return array Returns the list of loaded classes
     * @since 1.0-sofia
     */
    public function loaded(){
        return $this->loadedClasses;
    }
    
    /**
     * Loads classes based on the package supplied.
     * 
     * If your file is located in the framework folder:
     * <code>    packfire/yaml/pYaml.php</code>
     * The package name will then be:
     * <code>    packfire.yaml.pYaml</code>
     * 
     * If your file is located in the application folder:
     * <code    public/packfire/app/AppView.php</code>
     * The package name will then be:
     * <code>   app.AppView</code>
     * 
     * @param string $package The package to load. 
     * @since 1.0-sofia
     */
    public function load($package){
        if(array_key_exists($package, $this->loadedClasses)){
            return;
        }else{
            $this->loadedClasses[$package] = array();
        }
        
        $search = '';
        if(strpos($package, '.') === false){
            $search = self::prepareCurrentDirectoryFiles($package);
        }else{
            $search = self::prepareDirectorySearch($package);
        }
        $files = glob($search, GLOB_NOSORT);
        
        foreach($files as $f){
            $ok = include_once($f);
            if($ok){
                $this->loadedClasses[$package][] = basename($f, self::EXT);
            }
        }
    } 
    
    /**
     * Prepare to search for the package in the directory which the class that
     * called for loading is in.
     * @param string $package The package to load.
     * @return string Returns the path constructed to search for.
     * @since 1.0-sofia
     */
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
        $search = $path . $package . self::EXT;
        return $search;
    }
    
    /**
     * Prepare to search for the package
     * @param string $package The package to load.
     * @return string Returns the path constructed to search for.
     * @since 1.0-sofia
     */
    private static function prepareDirectorySearch($package){
        $packages = array_filter(explode('.', $package));
        $root = array_shift($packages);
        if($root == 'packfire'){
            $path = implode(DIRECTORY_SEPARATOR, $packages);
            $search = __PACKFIRE_ROOT__ . $path . self::EXT;
        }else{
            $path = 'pack' . DIRECTORY_SEPARATOR . ($root ? ($root . DIRECTORY_SEPARATOR) : '')
                . implode(DIRECTORY_SEPARATOR, $packages);
            $search = __APP_ROOT__ . $path . self::EXT;
        }
        return $search;
    }
    
    /**
     * Resolve a package-class string into package and class
     * 
     * Example:
     * <code>    pClassLoader::resolvePackageClass('packfire.ioc.pServiceBucket');</code>
     * will return:
     * <code>    array('packfire.ioc.pServiceBucket', 'pServiceBucket')</code>
     * 
     * @param string $packageClass The package-class string to resolve
     * @return array Returns the array containing the resolved package and class.
     * @since 1.0-sofia
     */
    public static function resolvePackageClass($packageClass){
        $result = array($packageClass, $packageClass);
        if(strpos($packageClass, '.') !== false){
            $class = end(explode('.'), $packageClass);
            return array($packageClass, $class);
        }
        return $result;
    }
    
}