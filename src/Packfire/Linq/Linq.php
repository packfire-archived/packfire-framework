<?php
namespace Packfire\Linq;

use ILinq;
use Packfire\Collection\ArrayList;
use LinqWhereQuery;
use LinqTakeQuery;
use LinqSkipQuery;
use LinqSelectQuery;
use LinqDistinctQuery;
use LinqReverseQuery;
use LinqOrderByQuery;
use LinqJoinQuery;
use LinqGroupByQuery;
use Packfire\Exception\NullException;
use OrderedLinq;

/**
 * Linq class
 * 
 * Provides functionality to perform LINQ queries on a collection.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq
 * @since 1.0-sofia
 */
class Linq implements ILinq, IteratorAggregate {
    
    /**
     * The queue of query processes
     * @var ArrayList
     * @since 1.0-sofia
     */
    private $queue;
    
    /**
     * The collection to work on
     * @var ArrayList
     * @since 1.0-sofia
     */
    private $collection;
    
    /**
     * Create a new Linq object
     * @param ArrayList|array $collection The collection to query
     * @param ArrayList $queries (optional) The list of queries. Internally used. 
     * @since 1.0-sofia
     */
    public function __construct($collection, $queries = null){
        if($queries){
            $this->queue = new ArrayList($queries);
        }else{
            $this->queue = new ArrayList();
        }
        if(is_array($collection) || $collection instanceof ArrayList){
            $collection = new ArrayList($collection);
        }
        $this->collection = $collection;
    }
    
    /**
     * Start the LINQ query from a source
     * @param ArrayList|array $source The collection to query and ork on
     * @param ArrayList $queries (optional) The list of queries. Internally used.
     * @return Linq Returns the Linq object for chaining.
     * @since 1.0-sofia
     */
    public static function from($source, $queries = null){
        return new self($source, $queries);
    }
    
    /**
     * Do a downcast if required.
     * @return Linq Returns the downcast if required.
     * @since 1.0-sofia
     */
    protected function orDowncast(){
        if(get_class($this) !== __CLASS__){
            return new self($this->collection, $this->queue);
        }
        return $this;
    }
    
    /**
     * Get the last query entered into the queue. 
     * @return ILinqQuery Returns the last query
     * @since 1.0-sofia
     */
    protected function lastQuery(){
        return empty($this->queue) ? null : end($this->queue);
    }
    
    /**
     * Execute the queries and get the finalized collection
     * @return array Returns the finalized collection
     * @since 1.0-sofia
     */
    protected function finalize(){
        $collection = $this->collection->toArray();
        foreach($this->queue as $query){
            $collection = $query->run($collection);
        }
        return $collection;
    }
    
    /**
     * Add a query to the queue
     * @param ILinqQuery $query The query to be added to the queue
     * @since 1.0-sofia 
     */
    protected function queueAdd($query){
        $this->queue->add($query);
    }
    
    /**
     * Calculate the average based on a field
     * @param Closure|callback $field The field selector
     * @return integer|double Returns the average from of the field.
     * @since 1.0-sofia
     */
    public function average($field = null) {
        if(!$field){
            $field = function($x){
                    return $x;
                };
        }
        $result = self::from($this->collection, $this->queue)->select($field)->finalize();
        return array_sum($result) / count($result);
    }

    /**
     * Count the number of elements
     * @param Closure|callback $condition (optional) Get the predicate to get the count.
     * @return integer Returns the number of elements
     * @since 1.0-sofia
     */
    public function count($condition = null) {
        $copy = self::from($this->collection, $this->queue);
        if($condition){
            $copy->where($condition);
        }
        return count($copy->finalize());
    }

    /**
     * Remove duplicate elements from the collection
     * @return Linq Returns the Linq object for chaining.
     * @since 1.0-sofia
     */
    public function distinct() {
        $this->queueAdd(new LinqDistinctQuery());
        return $this->orDowncast();
    }

    /**
     * Get the first element from the collection
     * @param Closure|callback $predicate (optional) The condition to filter the elements
     * @return mixed Returns the first element from the collection
     * @throws pNullException Thrown when there is no element in the collection.
     * @since 1.0-sofia
     */
    public function first($predicate = null) {
        $result = $this->firstOrDefault($predicate);
        if($result === null){
            throw new NullException('Linq::first() - Could not find first element as collection is empty.');
        }
        return $result;
    }
    
    /**
     * Get the first element from the collection
     * @param Closure|callback $predicate (optional) The condition to filter the elements
     * @return mixed Returns the first element from the collection is found or NULL if not.
     * @since 1.0-sofia
     */
    public function firstOrDefault($predicate = null) {
        if(empty($this->collection)){
            return null;
        }
        if($predicate){
            $collection = self::from($this->collection, $this->queue)->where($predicate)->toList()->toArray();
        }else{
            $collection = self::from($this->collection, $this->queue)->toList()->toArray();
        }
        if(empty($collection)){
            return null;
        }
        return reset($collection);
    }

    /**
     * Group the collection by a field
     * @param Closure|callback $field The field selector to group by
     * @return Linq Returns the Linq object for chaining purposes.
     * @since 1.0-sofia
     */
    public function groupBy($field) {
        $this->queueAdd(new LinqGroupByQuery($field));
        return $this->orDowncast();
    }

    /**
     * Correlates the elements of two collections based on matching keys. 
     * @param ArrayList $collection The other collection to join
     * @param Closure|callback $innerKey The inner key selector
     * @param Closure|callback $outerKey The outer key selector
     * @param Closure|callback $selector The result selector
     * @return Linq Returns the Linq object for chaining purposes.
     * @since 1.0-sofia
     */
    public function join($collection, $innerKey, $outerKey, $selector) {
        $this->queueAdd(new LinqJoinQuery($collection, $innerKey, $outerKey, $selector));
        return $this->orDowncast();
    }

    /**
     * Get the last element from the collection
     * @param Closure|callback $predicate (optional) The condition to filter the elements
     * @return mixed Returns the last element from the collection
     * @throws pNullException Thrown when there is no element in the collection.
     * @since 1.0-sofia
     */
    public function last($predicate = null) {
        $result = $this->lastOrDefault($predicate);
        if($result === null){
            throw new NullException('Linq::last() - Could not find last element as collection is empty.');
        }
        return $result;
    }
    
    /**
     * Get the last element from the collection
     * @param Closure|callback $predicate (optional) The condition to filter the elements
     * @return mixed Returns the last element from the collection is found or NULL if not.
     * @since 1.0-sofia
     */
    public function lastOrDefault($predicate = null) {
        if(empty($this->collection)){
            return null;
        }
        if($predicate){
            $collection = self::from($this->collection, $this->queue)->where($predicate)->toList()->toArray();
        }else{
            $collection = self::from($this->collection, $this->queue)->toList()->toArray();
        }
        if(empty($collection)){
            return null;
        }
        return end($collection);
    }

    /**
     * Filter the collection by offset and length
     * @param integer $offset The offset to start from
     * @param integer $length The amount to retrieve
     * @return Linq Returns the Linq object for chaining purposes.
     * @since 1.0-sofia
     */
    public function limit($offset, $length = null) {
        $major = $this->skip($offset);
        if($length){
            $major = $major->take($length);
        }
        return $major;
    }

    /**
     * Retrieve the maximum value from a field
     * @param Closure|callback $field (optional) The field selector
     * @return mixed Returns the maximum value
     * @since 1.0-sofia 
     */
    public function max($field = null) {
        return self::from($this->finalize())->orderBy($field)->lastOrDefault();
    }

    /**
     * Retrieve the minimum value from a field
     * @param Closure|callback $field (optional) The field selector
     * @return mixed Returns the minimum value
     * @since 1.0-sofia 
     */
    public function min($field = null) {
        return self::from($this->finalize())->orderBy($field)->firstOrDefault();
    }
    
    /**
     * Check if all the elements in the collection matches the predicate
     * @param Closure|callback $predicate The condition to check all the elements
     * @return boolean Returns true if all elements match the condition, false otherwise.
     * @since 1.0-sofia
     */
    public function all($predicate){
        $result = true;

        foreach ($this->collection as $value) {
            $result = $result && $predicate($value);
            if(!$result){
                break;
            }
        }

        return $result;
    }
    
    /**
     * Check if any of the elements in the collection matches the predicate
     * @param Closure|callback $predicate The condition to check all the elements
     * @return boolean Returns true if at least one element match the condition, false otherwise.
     * @since 1.0-sofia
     */
    public function any($predicate = null){
        if ($predicate === null && !empty($this->collection)) {
                return true;
        }

        foreach ($this->collection as $value) {
            if ($predicate($value)) {
                    return true;
            }
        }

        return false;
    }

    /**
     * Sort the collection by a field in an ascending order.
     * @param Closure|callback $field The field selector to sort by
     * @return pOrderedLinq Returns the pOrderedLinq object for further ordering or chaining.
     * @since 1.0-sofia
     */
    public function orderBy($field) {
        $this->queueAdd(new LinqOrderByQuery($field));
        return new OrderedLinq($this->collection, $this->queue);
    }

    /**
     * Sort the collection by a field in a descending order.
     * @param Closure|callback $field The field selector to sort by
     * @return pOrderedLinq Returns the pOrderedLinq object for further ordering or chaining.
     * @since 1.0-sofia
     */
    public function orderByDesc($field) {
        $this->queueAdd(new LinqOrderByQuery($field, true));
        return new OrderedLinq($this->collection, $this->queue);
    }

    /**
     * Select the collection for remapping
     * @param Closure|callback $mapper The remapping function
     * @return Linq Returns the Linq object for chaining purposes
     * @since 1.0-sofia
     */
    public function select($mapper) {
        $this->queueAdd(new LinqSelectQuery($mapper));
        return $this->orDowncast();
    }

    /**
     * Get the sum of a field
     * @param Closure|callback $field (optional) The field selector
     * @return integer|double
     */
    public function sum($field = null) {
        $worker = self::from($this->collection, $this->queue);
        if($field){
            $worker = $worker->select($field);
        }
        $result = $worker->finalize();
        return array_sum($result);
    }
    
    /**
     * Skip a certain amount of elements
     * @param integer $count The amount of elements to skip
     * @return Linq Returns the Linq object for chaining purposes
     * @since 1.0-sofia
     */
    public function skip($count){
        $this->queueAdd(new LinqSkipQuery($count));
        return $this->orDowncast();
    }
    
    /**
     * Take a certain amount of elements
     * @param integer $count The amount of elements to take
     * @return Linq Returns the Linq object for chaining purposes
     * @since 1.0-sofia
     */
    public function take($count){
        $this->queueAdd(new LinqTakeQuery($count));
        return $this->orDowncast();
    }
    
    /**
     * Set the condition to filter the elements
     * @param Closure|callback $condition The filtering condition
     * @return Linq Returns the Linq object for chaining purposes
     * @since 1.0-sofia
     */
    public function where($condition) {
        $this->queueAdd(new LinqWhereQuery($condition));
        return $this->orDowncast();
    }
    
    /**
     * Get the iterator for foreach access.
     * @return ArrayIterator Returns the ArrayIterator for access
     * @internal
     * @since 1.0-sofia
     */
    public function getIterator() {
        return new ArrayIterator($this->collection);
    }
    
    /**
     * Get the list of the elements
     * @return ArrayList Returns the resulting collection
     * @since 1.0-sofia
     */
    public function toList(){
        return new ArrayList($this->finalize());
    }

    /**
     * Reverse the collection
     * @return Linq Returns the Linq object for chaining purposes
     * @since 1.0-sofia
     */
    public function reverse() {
        $this->queueAdd(new LinqReverseQuery());
        return $this->orDowncast();
    }
    
}