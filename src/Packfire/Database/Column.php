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
 * A database table column
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
class Column
{
    /**
     * Name of the column
     * @var string
     * @since 1.0-sofia
     */
    private $name;

    /**
     * Data type of the column
     * @var string
     * @since 1.0-sofia
     */
    private $type;

    /**
     * Create a new Column object
     * @param string $name Name of the column
     * @param string $type Data type of the column
     * @since 1.0-sofia
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Get the name of the column
     * @return string Returns the name of the column
     * @since 1.0-sofia
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Get the data type of the column
     * @return string Returns the data type of the column
     * @since 1.0-sofia
     */
    public function type()
    {
        return $this->type;
    }
}
