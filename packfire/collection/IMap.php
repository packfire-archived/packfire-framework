<?php

interface IMap extends IList {
    
    public function add($key, $value);
    
    public function keyExists($key);
    
}