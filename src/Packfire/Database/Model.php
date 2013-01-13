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

use Packfire\Database\IModel;
use Packfire\Collection\Map;

/**
 * A generic abstract implementation for database models
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
abstract class Model implements IModel {
    
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