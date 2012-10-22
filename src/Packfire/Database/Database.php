<?php
namespace Packfire\Database;

/**
 * Database class
 * 
 * Abstraction of a database representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
abstract class Database {
    
    /**
     * The database connector
     * @var IConnector
     * @since 1.0-sofia
     */
    protected $driver;
    
    /**
     * Create a new Database object
     * @param IConnector $driver The connector to access database
     * @since 1.0-sofia
     */
    public function __construct($driver){
        $this->driver = $driver;
    }
    
    /**
     * Select a schema
     * @param string $schema The schema to work with
     * @return Schema Returns the selected schema
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