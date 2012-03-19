<?php

abstract class pDbSchema {
    
    /**
     *
     * @var pDbDriver
     */
    protected $driver;
    
    /**
     * The name of this schema
     * @var string
     */
    protected $name;
    
    public function __construct($driver, $name){
        $this->driver = $driver;
        $this->name = $name;
    }
    
    public function name(){
        return $this->name;
    }
    
    public abstract function create();
    
    public abstract function delete();
    
    public abstract function add($name, $columns);
    
    public abstract function remove($table);
    
    public abstract function table($table);
    
}
