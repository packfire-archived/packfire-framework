<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Database;

/**
 * Abstraction of a database table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
abstract class Table
{
    /**
     * The database connector
     * @var IConnector
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
     * Create a new Table object
     * @param IConnector $driver The connector
     * @param string     $name   The name of the table
     * @since 1.0-sofia
     */
    public function __construct($driver, $name)
    {
        $this->driver = $driver;
        $this->name = $name;
    }

    /**
     * Get the name of the table
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Add a column to the table
     * @param Column $column The new column to add to the table
     * @since 1.0-sofia
     */
    abstract public function add($column);

    /**
     * Remove a column from the table
     * @param string|Column $column The column to remove
     * @since 1.0-sofia
     */
    abstract public function remove($column);

    /**
     * Insert a row into the table
     * @param array|Map $row The row to insert into the table
     * @since 1.0-sofia
     */
    abstract public function insert($row);

    /**
     * Get a row from the table by its primary keys
     * @param  array|Map $row The row's primary keys
     * @return array
     * @since 1.0-sofia
     */
    abstract public function get($row);

    /**
     * Delete rows from the table
     * @param array|Map $row (optional) The conditions to delete the rows. If
     *          this is not specified, all rows from the table will be deleted.
     * @since 1.0-sofia
     */
    abstract public function delete($row = null);

    /**
     * Update a row in the table
     * @param array|Map $row The updated information. Primary key should be
     *           included here if $where is not set.
     * @param array|Map $where (optional) The conditions to update the rows
     * @since 1.0-sofia
     */
    abstract public function update($row, $where = null);

    /**
     * Get the columns of the table
     * @return ArrayList Returns a list of Column objects
     * @since 1.0-sofia
     */
    abstract public function columns();

    /**
     * Get the list of primary keys of the table
     * @return ArrayList Returns a list of Column objects
     * @since 1.0-sofia
     */
    abstract public function pk();
}
