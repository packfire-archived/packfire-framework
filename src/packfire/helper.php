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

/**
 * Elaborate the data type of a variable
 * @param mixed $var The variable to elaborate
 * @return string Returns the elaborated data type of the variable
 * @since 1.1-sofia
 */
function dtype($var){
    $result = gettype($var);
    if($result == 'object'){
        $result = get_class($var) . '/' . spl_object_hash($var);
    }elseif($result == 'resource'){
        $result = 'resource/' . get_resource_type($var) . '/' . intval($var);
    }
    return $result;
}