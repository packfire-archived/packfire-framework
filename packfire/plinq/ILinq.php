<?php

/**
 * Interface for LINQ operations
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
interface ILinq {
    
    public function where($conditionFunc);
    
    public function orderBy($field);
    
    public function orderByDesc($field);
    
    public function select($mapper);
    
    public function join($subject, $name, $conditionFunc);
    
    public function distinct();
    
    public function groupBy($field);
    
    public function count($condition = null);
    
    public function sum($field);
    
    public function min($field);
    
    public function max($field);
    
    public function average($field);
    
    public function limit($offset, $length = null);
    
    public function first($predicate = null);
    
    public function firstOrDefault($predicate = null);
    
    public function last($predicate = null);
    
    public function lastOrDefault($predicate = null);
    
    public function skip($count);
    
    public function take($count);
    
    public function all($predicate);
    
    public function any($predicate = null);
    
    public function reverse();
    
}