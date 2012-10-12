<?php

define('__PACKFIRE_START__', microtime(true));

define('__APP_ROOT__', '');

set_include_path(realpath(__DIR__ . '/../src')
        . PATH_SEPARATOR . get_include_path());

include('Packfire/constants.php');
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