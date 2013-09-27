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

use Packfire\Database\Connector\PdoConnector;
use Packfire\Database\Drivers\MySql\Database;

/**
 * Provides functionalities to and operations of a MySQL table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database\Drivers\MySql
 * @since 1.0-sofia
 */
class Connector extends PdoConnector
{
    /**
     * Translates data type
     * @param  string $type The input data type
     * @return string The translated data type
     * @since 1.0-sofia
     */
    public function translateType($type)
    {
        $types = array(
            'pk' => 'int(11) NOT NULL auto_increment',
            'string' => 'varchar(255)',
            'integer' => 'int(11)',
            'timestamp' => 'datetime',
            'binary' => 'blob',
            'boolean' => 'tinyint(1)'
        );
        if (isset($types[$type])) {
            return $types[$type];
        }

        return $type;
    }

    /**
     * Get the database representation
     * If there is no dbname defined in the config, the method returns a
     * Database object. If a dbname is defined, the method will select
     * the schema and return a Schema object.
     * @return Database|Schema Returns the database representation object
     * @since 1.0-sofia
     */
    public function database()
    {
        $database = new Database($this);
        if (isset($this->config['dbname']) && $this->config['dbname']) {
            $database = $database->select($this->config['dbname']);
        }

        return $database;
    }
}
