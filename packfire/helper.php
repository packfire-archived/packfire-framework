<?php
require(__PACKFIRE_ROOT__ . 'pClassLoader.php');

/**
 * The helper file where alias and functions are declared
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */

/**
 * The shortened alias of the class loader. 
 * @param string $package The package to load.
 * @since 1.0-sofia 
 */
function pload($package){
    static $loader = null;
    if(!$loader){
        $loader = new pClassLoader();
    }
    $loader->load($package);
}

/**
 * A self-executing function with scoping and context.
 * This function emulates the using keyword found in C#.
 * 
 * @param Closure|callback $func The function to execute
 * @return mixed
 * @example
 * <code>using($this->service('debugger'), function($debug){
 *      $debug->enabled(true);
 *      $debug->log('cool here');
 *      $debug->timeCheck();
 * });</code>
 * 
 * @since 1.0-sofia
 */
function using($func){
    if(func_num_args() > 1){
        $args = func_get_args();
        $func = array_pop($args);
        return call_user_func_array($func, $args);
    }else{
        return $func();
    }
}