<?php

/**
 * IDbModel interface
 * 
 * Database Model abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
interface IDbModel {
    
    /**
     * Get name of the model in the database 
     * @return string Returns the name of the model, or null if there isn't.
     * @since 1.0-sofia
     */
    public function dbName();
    
    /**
     * Get the mapping for the model
     * @return pMap|array Returns the mapping for the database modelling
     * @since 1.0-sofia
     */
    public function map();
    
}