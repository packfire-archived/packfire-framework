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

define('__PACKFIRE_CLASS__', '../packfire/Packfire.php');

include(__PACKFIRE_CLASS__);

Packfire::fire();