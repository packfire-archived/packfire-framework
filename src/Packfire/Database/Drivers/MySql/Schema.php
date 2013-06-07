<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Database\Drivers\MySql;

use Packfire\Database\SchemaLinq as DbSchema;
use Packfire\Database\Table as DbTable;
use Packfire\Database\Drivers\MySql\Table;
use Packfire\Database\Drivers\MySql\Linq\Linq;
use Packfire\Collection\ArrayList;

/**
 * Provides functionalities to and operations of a MySQL Schema
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database\Drivers\MySql
 * @since 1.0-sofia
 */
class Schema extends DbSchema
{
    /**
     * Add a new table to the schema
     * @param  string      $name    The name of the table
     * @param  array|IList $columns The list of columns belonging to the table
     * @return Table       Returns the table representation of the newly
     *                     created table.
     * @since 1.0-sofia
     */
    public function create($name, $columns)
    {
        $query = 'CREATE TABLE IF NOT EXISTS `%s` (';
        $columns = array();
        $primaryKeys = array();
        foreach ($columns as $column) {
            $columns[] = '`'. $column->name() . '` ' . $this->driver->translateType($column->type());
            if ($column->type() == 'pk') {
                $primaryKeys[] = 'PRIMARY KEY (`'. $column. '`)';
            }
        }
        $query .= implode(', ', $columns);
        if (count($primaryKeys) > 0) {
            $query .= ', ' . implode(', ', $primaryKeys);
        }
        $query .= ')';
        $this->driver->query(sprintf($query, $name));

        return $this->table($name);
    }

    /**
     * Delete a table from the schema
     * @param string|Table $table The table to delete
     * @since 1.0-sofia
     */
    public function delete($table)
    {
        if ($table instanceof DbTable) {
            $table = $table->name();
        }
        $this->driver->query('DROP `' . $this->name . '`.`' . $table . '`');
    }

    /**
     * Truncate / empty a table
     * @param string|Table $table The table to empty
     * @since 1.0-sofia
     */
    public function truncate($table)
    {
        if ($table instanceof DbTable) {
            $table = $table->name();
        }
        $this->driver->query(sprintf('TRUNCATE TABLE `%s`.`%s`', $this->name, $table));
    }

    /**
     * Get a table
     * @param  string $table Name of the table to fetch
     * @return Table  Returns the table representation
     * @since 1.0-sofia
     */
    public function table($table)
    {
        $table = new Table($this->driver, $table);
        $table->columns();

        return $table;
    }

    /**
     * Start the LINQ expression from a table
     * @param  string $table The table to work with
     * @return Linq   Returns the LINQ object to start chaining
     * @since 1.0-sofia
     */
    public function from($table)
    {
        return new Linq($this->driver, $table);
    }

    /**
     * Get a list of tables in the schema
     * @return ArrayList Returns a list of table names
     * @since 1.0-sofia
     */
    public function tables()
    {
        $statement = $this->driver->query('SHOW TABLES IN `' . $this->name . '`');
        $data = $statement->fetchAll();
        $tables = new ArrayList();
        foreach ($data as $row) {
            $tables[] = $row[0];
        }

        return $tables;
    }
}
