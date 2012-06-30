<?php
pload('packfire.database.pDatabase');

/**
 * pMySqlDatabase class
 * 
 * A MySQL Database representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.drivers.mysql
 * @since 1.0-sofia
 */
class pMySqlDatabase extends pDatabase {
    
    /**
     * Select a database schema for use
     * @param string $schema Name of the schema
     * @return pMySqlSchema Returns the schema representation
     * @since 1.0-sofia
     */
    public function select($schema) {
        $this->driver->query('USE `%s`', $schema);
        return new pMySqlSchema($this->driver, $schema);
    }

    /**
     * Create the schema
     * @param string $schema The name of the new schema
     * @since 1.0-sofia 
     */
    public function create($schema) {
        $this->driver->query('CREATE SCHEMA `' . $schema . '`');
    }

    /**
     * Delete the schema
     * @param string $schema The name of the schema to delete
     * @since 1.0-sofia 
     */
    public function delete($schema) {
        $this->driver->query('DROP SCHEMA `' . $schema . '`');
    }
    
}