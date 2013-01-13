<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Linq\Query;

use Packfire\Linq\IQuery;

/**
 * A LINQ Take query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq\Query
 * @since 1.0-sofia
 */
class Take implements IQuery {
    
    /**
     * The amount of elements to take
     * @var integer 
     * @since 1.0-sofia
     */
    private $count;
    
    /**
     * Create a new Take object
     * @param integer $count The amount of elements to take
     * @since 1.0-sofia
     */
    public function __construct($count){
        $this->count = $count;
    }
    
    /**
     * Execute the query
     * @param array $collection The collection to execute the query upon
     * @return array Returns the resulting array after the execution 
     * @since 1.0-sofia
     */
    public function run($collection) {
        return array_slice($collection, 0, $this->count);
    }
    
}