<?php

/**
 * This is not the directory of the web application root.
 * Instead, the web application root folder is in the public folder relative to
 * this file.
 * 
 * Visitors to this file will be redirected to the public folder.
 */


define('__PACKFIRE_PATH__', pathinfo(__FILE__, PATHINFO_DIRNAME) .
        DIRECTORY_SEPARATOR . 'packfire' . DIRECTORY_SEPARATOR);

define('__APP_ROOT__', __PACKFIRE_PATH__ . 'setup' . DIRECTORY_SEPARATOR);

define('__ENVIRONMENT__', 'setup');

$ok = include(__PACKFIRE_PATH__ . '/Packfire.php');
if($ok){
    pload('app.pSetupHttpApplication');
    $packfire = new Packfire();
    $packfire->fire(new pSetupHttpApplication());
}else{
    
}