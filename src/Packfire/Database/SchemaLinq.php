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

use Packfire\Database\Schema;

/**
 * Abstraction of a database schema that supports LINQ
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 2.0.0
 */
abstract class SchemaLinq extends Schema
{
    /**
     * Start the LINQ expression from a table
     * @param  string                  $table The table to work with
     * @return Packfire\Database\ILinq Returns the LINQ object to start chaining
     * @since 2.0.0-dev
     */
    abstract public function from($table);
}
