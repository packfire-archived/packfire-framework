<?php

define('__PACKFIRE_START__', microtime(true));
define('__APP_ROOT__', '');

require(__DIR__ . '/../src/Packfire/Packfire.php');

$packfire = new Packfire\Packfire();