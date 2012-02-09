<?php

interface ILinq {
    
    public function in($source);
    
    public function from($name);
    
    public function where($conditionFunc);
    
    public function orderBy($fieldSelector);
    
    public function thenBy($fieldSelector);
    
    public function orderByDesc($fieldSelector);
    
    public function thenByDesc($fieldSelector);
    
    /**
     *
     * @param type $mapper either function or array
     */
    public function select($mapper);
    
    public function join($subject, $name, $conditionFunc);
    
    public function distinct();
    
    public function group($fieldSelector);
    
    public function count($conditionFunc = null);
    
    public function sum($fieldSelector = null);
    
    public function min($fieldSelector = null);
    
    public function max($fieldSelector = null);
    
    public function average($fieldSelector = null);
    
    public function limit($offset, $length = null);
    
    public function first();
    
    public function last();
    
}