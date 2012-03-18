<?php

abstract class pDatabase {
    
    /**
     *
     * @var pDbDriver
     */
    protected $driver;
    
    public function __construct($driver){
        $this->driver = $driver;
    }
    
    /**
     * Select a schema
     * @param string The schema to work with
     * @return pDbSchema 
     */
    public abstract function select($schema);
    
}