<?php

/**
 * A MySQL LINQ query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.drivers.mysql.linq
 * @since 1.0-sofia
 */
interface IMySqlLinqQuery {
    
    /**
     * Create the statement
     * @return string Returns the resulting statement
     * @since 1.0-sofia
     */
    public function create();
    
}