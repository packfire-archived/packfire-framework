<?php

/**
 * Packfire Application Front Controller 
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @since 1.0-sofia
 * @internal
 * @ignore
 */

/**
 * Location of the Packfire class
 */
define('__PACKFIRE_CLASS__', '../packfire/Packfire.php');

// include the main Packfire class
$ok = @include(__PACKFIRE_CLASS__);
if($ok){
    // IMMA FIRIN' MA LAZOR
    $packfire = new Packfire();
    $packfire->fire();
}else{
    
}