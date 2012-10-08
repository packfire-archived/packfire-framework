<?php
namespace Packfire\Database\Drivers\MySql\Linq;

/**
 * ILinqQuery interface
 * 
 * A MySQL LINQ query interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database\Drivers\MySql\Linq
 * @since 1.0-sofia
 */
interface ILinqQuery {
    
    /**
     * Create the statement
     * @return string Returns the resulting statement
     * @since 1.0-sofia
     */
    public function create();
    
}