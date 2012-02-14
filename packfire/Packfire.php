<?php

/**
 * The root folder of the framework
 * @since 1.0-sofia
 */
define('__PACKFIRE_ROOT__', pathinfo(__FILE__, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR);

/**
 * The root folder of the application front controller
 * @since 1.0-sofia
 */
define('__APP_ROOT__', pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR);

require(__PACKFIRE_ROOT__ . 'helper.php');

/**
 * The small fire you bring around in your pack to go around setting forests
 * on flames. Spark your web applications with Packfire today!
 *
 * @link http://www.github.com/thephpdeveloper/packfire
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class Packfire {
    
    public static function load($path){
        pload($path);
    }
    
    public static function fire(){
        pload('packfire.config.pConfig');
        $config = new pConfig('app', '');
    }
    
}