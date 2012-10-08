<?php
namespace Packfire\Database;

/**
 * Schema class
 * 
 * Abstraction of a database schema
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
abstract class Schema {
    
    /**
     * The database driver
     * @var IConnector
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
     * Create a new Schema object
     * @param IConnector $driver The database driver
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
    
    /**
     * Create a new table in the schema
     * @param string $name The name of the table
     * @param array|IList $columns The list of columns belonging to the table
     * @return Table Returns the table representation of the newly
     *                  created table.
     * @since 1.0-sofia
     */
    public abstract function create($name, $columns);
    
    /**
     * Delete a table from the schema
     * @param string|Table $table The table to delete
     * @since 1.0-sofia
     */
    public abstract function delete($table);
    
    /**
     * Truncate / empty a table
     * @param string|Table $table The table to empty
     * @since 1.0-sofia
     */
    public abstract function truncate($table);
    
    /**
     * Fetch a table representation
     * @param string $table Name of the table to fetch
     * @return Table Returns the table representation
     * @since 1.0-sofia
     */
    public abstract function table($table);
    
    /**
     * Start the LINQ expression from a table
     * @param string $table The table to work with
     * @return Packfire\Database\ILinq Returns the LINQ object to start chaining
     * @since 1.0-sofia
     */
    public abstract function from($table);
    
    /**
     * Get a list of tables in the schema
     * @return pList Returns a list of table names
     * @since 1.0-sofia 
     */
    public abstract function tables();
    
}
