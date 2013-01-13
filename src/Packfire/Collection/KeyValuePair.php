<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Collection;

/**
 * A Key and Value pair representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
class KeyValuePair {
   
    /**
     * The key that identifies the value.
     * @var string|integer
     * @since 1.0-sofia
     */
    private $key;

    /**
     * The value
     * @var mixed
     * @since 1.0-sofia
     */
    private $value;

    /**
     * Create a new KeyValuePair object
     * @param string $key The key name
     * @param mixed $value The value
     * @since 1.0-sofia
     */
    function __construct($key, $value) {
        $this->key($key);
        $this->value($value);
    }

    /**
     * Get or set the key name
     * @param string|integer $key (optional) Set the key name
     * @return string|integer Returns the key name
     * @since 1.0-sofia
     */
    public function key($key = false){
        if(func_num_args() == 1){
            $this->key = $key;
        }
        return $this->key;
    }

    /**
     * Get or set the value of the KeyValuePair
     * @param mixed $value (optional) Set the value
     * @return mixed Returns the value
     * @since 1.0-sofia
     */
    public function value($value = false){
        if(func_num_args() == 1){
            $this->value = $value;
        }
        return $this->value;
    }
    
}