<?php

/**
 * Abstraction of a database representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
abstract class pDatabase {
    
    /**
     * The database connector
     * @var pDbConnector
     * @since 1.0-sofia
     */
    protected $driver;
    
    /**
     * Create a new pDatabase object
     * @param pDbConnector $driver The connector to access database
     * @since 1.0-sofia
     */
    public function __construct($driver){
        $this->driver = $driver;
    }
    
    /**
     * Select a schema
     * @param string $schema The schema to work with
     * @return pDbSchema Returns the selected schema
     * @since 1.0-sofia
     */
    public abstract function select($schema);
    
    /**
     * Create a new database schema 
     * @param string $schema The name of the new schema
     * @since 1.0-sofia
     */
    public abstract function create($schema);
    
    /**
     * Delete an existing database schema 
     * @param string $schema The name of the schema to delete
     * @since 1.0-sofia
     */
    public abstract function delete($schema);
    
}