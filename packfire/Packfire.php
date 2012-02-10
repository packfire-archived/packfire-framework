<?php


define('__PACKFIRE_ROOT__', pathinfo(__FILE__, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR);

require(__PACKFIRE_ROOT__ . 'pClassLoader.php');

/**
 * The core magical class. Unicorns ftw.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class Packfire {
    
    public static function load($path){
        pClassLoader::load($path);
    }
    
}