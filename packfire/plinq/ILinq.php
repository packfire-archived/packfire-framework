<?php

/**
 * Interface for LINQ operations
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire/plinq
 * @since 1.0-sofia
 */
interface ILinq {
    
    public function in($source);
    
    public function from($name);
    
    public function where($conditionFunc);
    
    public function orderBy($field);
    
    public function thenBy($field);
    
    public function orderByDesc($field);
    
    public function thenByDesc($field);
    
    public function select($mapper);
    
    public function join($subject, $name, $conditionFunc);
    
    public function distinct();
    
    public function group($field);
    
    public function count($condition = null);
    
    public function sum($field = null);
    
    public function min($field = null);
    
    public function max($field = null);
    
    public function average($field = null);
    
    public function limit($offset, $length = null);
    
    public function first();
    
    public function last();
    
}