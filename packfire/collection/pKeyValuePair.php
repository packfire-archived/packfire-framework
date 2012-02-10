<?php

class pKeyValuePair {
   
    /**
     * Key
     * @var string|integer
     */
    private $key;

    /**
     * Value
     * @var mixed
     */
    private $value;

    /**
     * Create a new pKeyValuePair with key and value
     * @param string $key
     * @param mixed $value
     */
    function __construct($key, $value) {
        $this->key($key);
        $this->value($value);
    }

    /**
     * Key of the pKeyValuePair
     * @param string|integer $k (optional)
     * @return string|integer
     */
    public function key($k = false){
        if(func_num_args() == 1){
            $this->key = $k;
        }
        return $this->key;
    }

    /**
     * Value of the pKeyValuePair
     * @param mixed $v (optional) 
     * @return mixed
     */
    public function value($v = false){
        if(func_num_args() == 1){
            $this->value = $v;
        }
        return $this->value;
    }
    
}