<?php
require(__PACKFIRE_ROOT__ . 'pClassLoader.php');

function pload($path){
    static $loader = null;
    if(!$loader){
        $loader = new pClassLoader();
    }
    $loader->load($path);
}
