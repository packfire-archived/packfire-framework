<?php
namespace Packfire;

/**
 * Bootstrap class
 * 
 * Prepares for bootstrapping the framework
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire
 * @since 2.0.0
 */
class Bootstrap {
    
    /**
     * Initializes the bootstrapping process
     * @since 2.0.0
     */
    public static function initialize(){
        require __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';
        
        set_include_path(dirname(__DIR__)
                . PATH_SEPARATOR . get_include_path());
        spl_autoload_register(function($class) {
            $class = ltrim($class, '\\');
            $fileName  = '';
            $namespace = '';
            $lastNsPos = strrpos($class, '\\');
            if($lastNsPos){
                $namespace = substr($class, 0, $lastNsPos);
                $class = substr($class, $lastNsPos + 1);
                $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace)
                        . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
            require_once($fileName);
        });
    }
    
}