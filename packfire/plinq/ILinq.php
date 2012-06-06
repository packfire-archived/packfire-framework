<?php

/**
 * Interface for LINQ operations
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
interface ILinq extends Countable {
    
    public static function from($source);
    
    public function where($conditionFunc);
    
    public function orderBy($field);
    
    public function orderByDesc($field);
    
    public function select($mapper);
    
    public function join($collection, $innerKey, $outerKey, $selector);
    
    public function distinct();
    
    public function groupBy($field);
    
    public function sum($field = null);
    
    public function min($field = null);
    
    public function max($field = null);
    
    public function average($field = null);
    
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