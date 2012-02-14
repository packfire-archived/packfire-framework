<?php
require(__PACKFIRE_ROOT__ . 'pClassLoader.php');

function pload($path){
    pClassLoader::load($path);
}
