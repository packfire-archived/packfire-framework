<?php
pload('packfire.collection.pMap');

abstract class pDbTable {
    
    /**
     *
     * @var pDbConnector
     */
    protected $driver;
    
    /**
     * The name of this table
     * @var string
     */
    protected $name;
    
    /**
     *
     * @param pDbConnector $driver
     * @param string $name 
     * @since 1.0-sofia
     */
    public function __construct($driver, $name){
        $this->driver = $driver;
        $this->name = $name;
    }
    
    public function name(){
        return $this->name;
    }
    
    public abstract function add($column);
    
    public abstract function remove($column);
    
    public abstract function insert($row);
    
    public abstract function get($row);
    
    public abstract function delete($row);
    
    public abstract function update($row);
    
    public abstract function columns();
    
    public abstract function pk();
    
}