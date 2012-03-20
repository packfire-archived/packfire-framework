<?php

/**
 * Abstraction of a database schema
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
abstract class pDbSchema {
    
    /**
     * The database driver
     * @var pDbDriver
     * @snce 1.0-sofia
     */
    protected $driver;
    
    /**
     * The name of this schema
     * @var string
     * @snce 1.0-sofia
     */
    protected $name;
    
    /**
     * Create a new pDbSchema
     * @param pDbDriver $driver The database driver
     * @param string $name Name of the schema
     * @since 1.0-sofia
     */
    public function __construct($driver, $name){
        $this->driver = $driver;
        $this->name = $name;
    }
    
    /**
     * Get the name of the schema
     * @return string Returns the name of the schema
     * @since 1.0-sofia
     */
    public function name(){
        return $this->name;
    }
    
    public abstract function create();
    
    public abstract function delete();
    
    public abstract function add($name, $columns);
    
    public abstract function remove($table);
    
    public abstract function table($table);
    
}
