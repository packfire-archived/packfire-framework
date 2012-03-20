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
    
    /**
     * Translate the data types 
     */
    public abstract function translateType($type);
    
    public abstract function escape($string);
    
}