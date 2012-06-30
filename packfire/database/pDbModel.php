<?php
pload('IDbModel');
pload('packfire.pModel');
pload('packfire.collection.pMap');

/**
 * pDbModel abstract class
 * 
 * A generic abstract implementation for database models
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
abstract class pDbModel extends pModel implements IDbModel {
    
    /**
     * Get name of the model in the database 
     * @return string Returns the name of the model, or null if there isn't.
     * @since 1.0-sofia
     */
    public function dbName(){
        return strtolower(get_class($this)) . 's';
    }
    
    /**
     * Get the mapping for the model
     * @return pMap Returns the mapping for the database modelling
     * @since 1.0-sofia
     */
    public function map(){
        $map = new pMap();
        $properties = array_keys(get_object_vars($this));
        foreach($properties as $key){
            $map->add($key, $key);
        }
        return $map;
    }
    
}