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

use Packfire\Database\Table as DbTable;
use Packfire\Database\Column;
use Packfire\Database\Drivers\MySql\Linq\Linq;
use Packfire\Collection\ArrayList;

/**
 * Provides functionalities to and operations of a MySQL table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database\Drivers\MySql
 * @since 1.0-sofia
 */
class Table extends DbTable
{
    /**
     * The connection driver
     * @var Connector
     * @since 1.0-sofia
     */
    protected $driver;

    /**
     * The list of columns in the table
     * @var ArrayList
     * @since 1.0-sofia
     */
    protected $columns;

    /**
     * The list of primary keys in the table
     * @var ArrayList
     * @since 1.0-sofia
     */
    protected $primaryKeys;

    /**
     * Add a column to the table
     * @param Column $column The new column to add to the table
     * @since 1.0-sofia
     */
    public function add($column)
    {
        $this->driver->query(
            sprintf(
                'ALTER TABLE `%s` ADD COLUMN `%s` %s',
                $this->name,
                $column->name(),
                $this->driver->translateType($column->type())
            )
        );
        $this->columns();
        $this->columns->add($column);
    }

    /**
     * Remove a column from the table
     * @param string|Column $column The column or the name of the column to
     *              remove from the table.
     * @since 1.0-sofia
     */
    public function remove($column)
    {
        if ($column instanceof Column) {
            $column = $column->name();
        }
        $this->driver->query(sprintf('ALTER TABLE `%s` DROP `%s`', $this->name, $column));
        $this->columns();
        foreach ($this->columns as $item) {
            if ($item->name() == $column) {
                $this->columns->remove($item);
                break;
            }
        }
    }

    /**
     * Get a row from the table by its primary keys
     * @param  array|Map $row The row's primary keys
     * @return array     Returns the row data
     * @since 1.0-sofia
     */
    public function get($row)
    {
        $linq = new Linq($this->driver, $this->name);
        $pks = array();
        $params = array();
        foreach ($this->pk() as $column) {
            if (array_key_exists($column->name(), $row)) {
                $pks[] = '`' . $column->name() . '` = :' . $column->name();
                $params[$column->name()] = $row[$column->name()];
            }
        }
        $query .= implode(' AND ', $pks);

        return $linq->where($query)->params($params)->fetch()->first();
    }

    /**
     * Delete rows from the table
     * @param array|Map $row (optional) The conditions to delete the rows. If
     *          this is not specified, all rows from the table will be deleted.
     * @since 1.0-sofia
     */
    public function delete($row = null)
    {
        $query = 'DELETE FROM `%s`';
        if ($row) {
            $query .= ' WHERE ';
            $where = array();
            $params = array();
            foreach ($this->columns() as $column) {
                if (array_key_exists($column->name(), $row)) {
                    $where[] = '`' . $column->name() . '` = :' . $column->name();
                    $params[$column->name()] = $row[$column->name()];
                }
            }
            $query .= implode(' AND ', $where);
            $statement = $this->driver->binder(sprintf($query, $this->name), $params);
        } else {
            $statement = $this->driver->prepare(sprintf($query, $this->name));
        }
        $statement->execute();
    }

    /**
     * Insert a row into the table
     * @param array|Map $row The row to insert into the table
     * @since 1.0-sofia
     */
    public function insert($row)
    {
        $query = 'INSERT INTO `%s` (';
        $columns = array();
        $values = array();
        $params = array();
        foreach ($this->columns() as $column) {
            if (array_key_exists($column->name(), $row)) {
                $columns[] = '`' . $column->name() . '`';
                $params[$column->name()] = $row[$column->name()];
                $values[] = ':' . $column->name();
            }
        }
        $query .= implode(', ', $columns) . ') VALUES (';
        $query .= implode(', ', $values) . ')';
        $statement = $this->driver->binder(sprintf($query, $this->name), $params);
        $statement->execute();
    }

    /**
     * Update a row in the table
     * @param array|Map $row The updated information. Primary key should be
     *           included here if $where is not set.
     * @param array|Map $where (optional) The conditions to update the rows
     * @since 1.0-sofia
     */
    public function update($row, $where = null)
    {
        $query = 'UPDATE `%s` SET ';
        $data = array();
        $pks = array();
        $params = array();
        foreach ($this->columns() as $column) {
            if (array_key_exists($column->name(), $row)) {
                $params[$column->name()] = $row[$column->name()];
                if ($column->type() == 'pk' && !$where) {
                    $pks[] = '`'.$column->name().'` = :' . $column->name();
                } elseif ($column->type() != 'pk') {
                    $data[] = '`'.$column->name().'` = :' . $column->name();
                }
            }
        }
        $query .= implode(', ', $data) . ' WHERE ';
        if ($where) {
            foreach ($this->columns() as $column) {
                if (array_key_exists($column->name(), $where)) {
                    $params[$column->name()] = $where[$column->name()];
                    $pks[] = '`' . $column->name() . '` = :' . $column->name();
                }
            }
        }
        $query .= implode(' AND ', $pks);
        $statement = $this->driver->binder(sprintf($query, $this->name), $params);
        $statement->execute();
    }

    /**
     * Get the columns of the table
     * @return ArrayList Returns a list of Column objects
     * @since 1.0-sofia
     */
    public function columns()
    {
        if (!$this->columns) {
            $statement = $this->driver->query(sprintf('SHOW COLUMNS FROM `%s`', $this->name));
            if ($statement) {
                $cols = $statement->fetchAll();
                $columns = new ArrayList();
                foreach ($cols as $col) {
                    $type = array();
                    if ($col[3] == 'PRI') {
                        $type[] = 'pk';
                    } else {
                        if ($col[1]) {
                            $type[] = $col[1];
                        }
                        if ($col[2] == 'NO') {
                            $type[] = 'NOT NULL';
                        }
                        if ($col[4]) {
                            $type[] = $col[4];
                        }
                    }
                    $types = implode(' ', $type);
                    $column = new Column($col[0], $types);
                    $columns->add($column);
                }
                $this->columns = $columns;
            }
        }

        return $this->columns;
    }

    /**
     * Get the list of primary keys of the table
     * @return ArrayList Returns a list of Column objects of the primary keys
     * @since 1.0-sofia
     */
    public function pk()
    {
        if (!$this->primaryKeys) {
            $statement = $this->driver->query(sprintf('SHOW COLUMNS FROM `%s` WHERE `Key` =  \'PRI\'', $this->name));
            $cols = $statement->fetchAll();
            $columns = new ArrayList();
            foreach ($cols as $col) {
                $columns[] = new Column($col[0], 'pk');
            }
            $this->primaryKeys = $columns;
        }

        return $this->primaryKeys;
    }
}
