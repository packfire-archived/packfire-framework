<?php
namespace Packfire\Database;

use Schema;

/**
 * SchemaLinq class
 * 
 * Abstraction of a database schema that supports LINQ
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 2.0.0-dev
 */
abstract class SchemaLinq extends Schema {    
    
    /**
     * Start the LINQ expression from a table
     * @param string $table The table to work with
     * @return Packfire\Database\ILinq Returns the LINQ object to start chaining
     * @since 2.0.0-dev
     */
    public abstract function from($table);
    
}
