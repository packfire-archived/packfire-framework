<?php
namespace Packfire\Database;

use IModel;
use Packfire\Model\Model as CoreModel;
use Packfire\Collection\Map;

/**
 * Model class
 * 
 * A generic abstract implementation for database models
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
abstract class Model extends CoreModel implements IModel {
    
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
     * @return Map Returns the mapping for the database modelling
     * @since 1.0-sofia
     */
    public function map(){
        $map = new Map();
        $properties = array_keys(get_object_vars($this));
        foreach($properties as $key){
            $map->add($key, $key);
        }
        return $map;
    }
    
}