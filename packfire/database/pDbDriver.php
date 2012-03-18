<?php

abstract class pDbDriver {
    
    /**
     * Connection
     * @var pDbConnection
     */
    protected $connection;
    
    public function __construct($connection){
        $this->connection = $connection;
    }
    
    public abstract function query($query);
    
    /**
     * Translate the data types 
     */
    public abstract function translateType($type);
    
}