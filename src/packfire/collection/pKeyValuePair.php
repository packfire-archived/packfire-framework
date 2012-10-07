<?php

/**
 * A Key and Value pair class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection
 * @since 1.0-sofia
 */
class pKeyValuePair {
   
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
     * Create a new pKeyValuePair with key and value
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
     * Get or set the value of the pKeyValuePair
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