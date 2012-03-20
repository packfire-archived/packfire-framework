<?php

class pDbConnection {
    
    private $config;
    
    private $driver;
    
    public function __construct($config){
        $this->config = $config;
        $this->driver = pDbDriverFactory::create($config);
    }
    
    public function driver(){
        return $this->driver;
    }
    
}