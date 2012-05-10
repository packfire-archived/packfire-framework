<?php

/**
 * Setup
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */

define('__PACKFIRE_PATH__', pathinfo(__FILE__, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR);

$ok = include(__PACKFIRE_PATH__ . '/Packfire.php');
if($ok){
    pload('packfire.setup.pSetupApplication');
    $packfire = new Packfire();
    $packfire->fire(new pSetupApplication());
}else{
    
}