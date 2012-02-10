<?php

class pRouter {
    
    const KEY = '_urlroute_';
    
    private $routes;
    
    public function __construct(){
        $this->routes = new pMap();
    }
    
}