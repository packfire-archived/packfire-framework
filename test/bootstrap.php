<?php

use Packfire\Core\ClassLoader\ClassLoader;
use Packfire\Core\ClassLoader\ClassFinder;

define('__PACKFIRE_START__', microtime(true));
define('__APP_ROOT__', '');

call_user_func(function(){
    set_include_path(realpath(__DIR__ . DIRECTORY_SEPARATOR . '..')
            . PATH_SEPARATOR . get_include_path());
    
    require('src/Packfire/constants.php');

    require('src/Packfire/Core/ClassLoader/ClassFinder.php');
    require('src/Packfire/Core/ClassLoader/IClassLoader.php');
    require('src/Packfire/Core/ClassLoader/ClassLoader.php');

    $finder = new ClassFinder();
    $finder->addNamespace('Packfire', 'src');
    $loader = new ClassLoader($finder);
    $loader->register();
});