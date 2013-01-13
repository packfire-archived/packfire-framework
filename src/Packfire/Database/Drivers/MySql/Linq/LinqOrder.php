<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Database\Drivers\MySql\Linq;

use Packfire\Database\Drivers\MySql\Linq\ILinqQuery;

/**
 * A MySQL LINQ Order statement
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database\Drivers\MySql\Linq
 * @since 1.0-sofia
 */
class LinqOrder implements ILinqQuery {
    
    /**
     * The field to sort
     * @var string
     * @since 1.0-sofia
     */
    private $field;
 
    /**
     * Flags whether the order is descending or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $descending;
    
    /**
     * Create a new LinqOrder object
     * @param string $field The field to sort
     * @param boolean $descending (optional) Sets whether the order is descending
     *                or not, defaults to false.
     * @since 1.0-sofia
     */
    public function __construct($field, $descending = false){
        $this->descending = $descending;
        $this->field = $field;
    }
    
    /**
     * Create and get the statement
     * @return string Returns the resulting statement
     * @since 1.0-sofia
     */
    public function create(){
        return $this->field . ($this->descending ? ' DESC': '');;
    }
    
}