<?php
pload('packfire.database.pDbSchema');
pload('packfire.database.pDbTable');
pload('pMySqlTable');
pload('packfire.database.drivers.mysql.linq.pMySqlLinq');

/**
 * Provides functionalities to and operations of a MySQL Schema
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.drivers.mysql
 * @since 1.0-sofia
 */
class pMySqlSchema extends pDbSchema {
    
    /**
     * Add a new table to the schema
     * @param string $name The name of the table
     * @param array|IList $columns The list of columns belonging to the table
     * @return pMySqlTable Returns the table representation of the newly
     *                     created table.
     * @since 1.0-sofia
     */
    public function create($name, $columns) {
        $query = 'CREATE TABLE IF NOT EXISTS `%s` (';
        $columns = array();
        $primaryKeys = array();
        foreach($columns as $column){
            $columns[] = '`'. $column->name() . '` ' . $this->driver->translateType($column->type());
            if($column->type() == 'pk'){
                $primaryKeys[] = 'PRIMARY KEY (`'. $column. '`)';
            }
        }
        $query .= implode(', ', $columns);
        if(count($primaryKeys) > 0){
            $query .= ', ' . implode(', ', $primaryKeys);
        }
        $query .= ')';
        $this->driver->query(sprintf($query, $name));
        return $this->table($name);
    }

    /**
     * Delete a table from the schema
     * @param string|pDbTable $table The table to delete
     * @since 1.0-sofia
     */
    public function delete($table) {
        if($table instanceof pDbTable){
            $table = $table->name();
        }
        $this->driver->query('DROP `' . $this->name . '`.`' . $table . '`');
    }
    
    /**
     * Truncate / empty a table
     * @param string|pDbTable $table The table to empty
     * @since 1.0-sofia
     */
    public function truncate($table){
        if($table instanceof pDbTable){
            $table = $table->name();
        }
        $this->driver->query(sprintf('TRUNCATE TABLE `%s`.`%s`', $this->name, $table));
    }

    /**
     * Get a table
     * @param string $table Name of the table to fetch
     * @return pMySqlTable Returns the table representation
     * @since 1.0-sofia
     */
    public function table($table) {
        $table = new pMySqlTable($this->driver, $table);
        $table->columns();
        return $table;
    }
    
    /**
     * Start the LINQ expression from a table
     * @param string $table The table to work with
     * @return pMySqlLinq Returns the LINQ object to start chaining
     * @since 1.0-sofia
     */
    public function from($table){
        return pMySqlLinq::from($table);
    }
    
}