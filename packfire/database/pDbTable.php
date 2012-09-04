<?php
pload('packfire.collection.pMap');

/**
 * pDbTable abstract class
 * 
 * Abstraction of a database table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
abstract class pDbTable {
    
    /**
     * The database connector
     * @var pDbConnector
     * @since 1.0-sofia
     */
    protected $driver;
    
    /**
     * The name of this table
     * @var string
     * @since 1.0-sofia
     */
    protected $name;
    
    /**
     * Create a new pDbTable object
     * @param pDbConnector $driver The connector
     * @param string $name The name of the table 
     * @since 1.0-sofia
     */
    public function __construct($driver, $name){
        $this->driver = $driver;
        $this->name = $name;
    }
    
    /**
     * Get the name of the table
     * @return string
     */
    public function name(){
        return $this->name;
    }

    /**
     * Add a column to the table
     * @param pDbColumn $column The new column to add to the table
     * @since 1.0-sofia
     */    
    public abstract function add($column);
    
    /**
     * Remove a column from the table
     * @param string|pDbColumn $column The column to remove
     * @since 1.0-sofia
     */
    public abstract function remove($column);
    
    /**
     * Insert a row into the table
     * @param array|pMap $row The row to insert into the table
     * @since 1.0-sofia
     */
    public abstract function insert($row);
    
    /**
     * Get a row from the table by its primary keys
     * @param array|pMap $row The row's primary keys
     * @return array
     * @since 1.0-sofia
     */
    public abstract function get($row);
    
    /**
     * Delete rows from the table
     * @param array|pMap $row (optional) The conditions to delete the rows. If
     *          this is not specified, all rows from the table will be deleted.
     * @since 1.0-sofia
     */
    public abstract function delete($row = null);
    
    /**
     * Update a row in the table
     * @param array|pMap $row The updated information. Primary key should be
     *           included here if $where is not set.
     * @param array|pMap $where (optional) The conditions to update the rows
     * @since 1.0-sofia
     */
    public abstract function update($row, $where = null);
        
    /**
     * Get the columns of the table
     * @return pList Returns a list of pDbColumn objects
     * @since 1.0-sofia
     */
    public abstract function columns();
        
    /**
     * Get the list of primary keys of the table
     * @return pList Returns a list of pDbColumn objects
     * @since 1.0-sofia
     */
    public abstract function pk();
    
}