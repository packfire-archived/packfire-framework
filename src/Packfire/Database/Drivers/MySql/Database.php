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

use Packfire\Database\Database as CoreDatabase;
use Packfire\Database\Drivers\MySql\Schema;

/**
 * A MySQL Database representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database\Drivers\MySql
 * @since 1.0-sofia
 */
class Database extends CoreDatabase {
    
    /**
     * Select a database schema for use
     * @param string $schema Name of the schema
     * @return Schema Returns the schema representation
     * @since 1.0-sofia
     */
    public function select($schema) {
        $this->driver->query('USE `' . $schema . '`');
        return new Schema($this->driver, $schema);
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