<?php
namespace Packfire;

use Packfire\Exception\MissingDependencyException;

/**
 * ClassLoader class
 * 
 * Loads and prepares other classes for use.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire
 * @since 1.0-sofia
 */
class ClassLoader {
    
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
    private static $loadedClasses = array();
    
    /**
     * Get the list of loaded classes
     * @return array Returns the list of loaded classes
     * @since 1.0-sofia
     */
    public static function loaded(){
        return self::$loadedClasses;
    }
    
    /**
     * Loads classes based on the package supplied.
     * 
     * If your file is located in the framework folder:
     * <code>    packfire/yaml/pYaml.php</code>
     * The package name will then be:
     * <code>    packfire.yaml.Yaml</code>
     * 
     * If your file is located in the application folder:
     * <code>    public/packfire/app/AppView.php</code>
     * The package name will then be:
     * <code>   app.AppView</code>
     * 
     * @param string $package The package to load. 
     * @since 1.0-sofia
     */
    public static function load($package){
        $lastpos = strrpos($package, '.');
        $class = $package;
        if($lastpos !== false){
            $class = substr($package, $lastpos + 1);
        }
        if(class_exists($class)){
            return;
        }
        
        $search = '';
        if(strpos($package, '.') === false){
            $search = self::prepareCurrentDirectoryFiles($package);
        }else{
            $search = self::prepareDirectorySearch($package);
        }
        try{
            include_once($search);
        }catch(Exception $ex){
            throw new MissingDependencyException('Dependency required but not found: "' . $package . '"');
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
        $trace = $a[2];
        $search = pathinfo($trace['file'], PATHINFO_DIRNAME)
                . DIRECTORY_SEPARATOR . $package . self::EXT;
        return $search;
    }
    
    /**
     * Prepare to search for the package
     * @param string $package The package to load.
     * @return string Returns the path constructed to search for.
     * @since 1.0-sofia
     */
    private static function prepareDirectorySearch($package){
        if(substr($package, 0, 9) == 'packfire.'){
            $path = str_replace('.', DIRECTORY_SEPARATOR, substr($package, 8));
            $search = __PACKFIRE_ROOT__ . $path . self::EXT;
        }else{
            $path = 'pack' . DIRECTORY_SEPARATOR
                    . str_replace('.', DIRECTORY_SEPARATOR, $package);
            $search = __APP_ROOT__ . $path . self::EXT;
        }
        return $search;
    }
    
    /**
     * Resolve a package-class string into package and class
     * 
     * Example:
     * <code>    ClassLoader::resolvePackageClass('packfire.ioc.pServiceBucket');</code>
     * will return:
     * <code>    array('packfire.ioc.pServiceBucket', 'pServiceBucket')</code>
     * 
     * @param string $packageClass The package-class string to resolve
     * @return array Returns the array containing the resolved package and class.
     * @since 1.0-sofia
     */
    public static function resolvePackageClass($packageClass){
        $result = array($packageClass, $packageClass);
        $dotPos = strrpos($packageClass, '.');
        if($dotPos !== false){
            $class = substr($packageClass, $dotPos + 1);
            return array($packageClass, $class);
        }
        return $result;
    }
    
    /**
     * Reverse engineer a given package into a path to the class
     * @param string $package The full package to reverse engineer
     * @return string Returns the path after reverse engineering
     * @since 1.0-sofia
     */
    public static function reverseEngineer($package){
        $framework = false;
        if(strpos($package, '.') !== false){
            $part = explode('.', $package);
            $framework = (count($part) > 1 && 'packfire' == reset($part));
        }
        $path = null;
        if($framework){
            $path = __PACKFIRE_ROOT__ . str_replace('.', DIRECTORY_SEPARATOR, substr($package, 9));
        }else{
            $path = __APP_ROOT__ . str_replace('.', DIRECTORY_SEPARATOR, $package);
        }
        $path .= self::EXT;
        return $path;
    }
    
}