<?php
namespace Packfire\Database\Drivers\MySql\Linq;

use Packfire\Linq\ILinq;
use Packfire\Linq\IOrderedLinq;
use Packfire\Database\IModel;
use Packfire\Database\Drivers\MySql\Table;
use Packfire\Collection\ArrayList;
use Packfire\Collection\Map;
use Packfire\Database\Drivers\MySql\Linq\LinqJoin;
use Packfire\Database\Drivers\MySql\Linq\LinqOrder;
use Packfire\Exception\NullException;

/**
 * Linq class
 * 
 * Provides LINQ functionality to a MySQL table on top of the existing MySQL
 * table functionalities.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database\Drivers\MySql\Linq
 * @since 1.0-sofia
 */
class Linq extends Table implements ILinq, IOrderedLinq {

    /**
     * The list of selects
     * @var ArrayList
     * @since 1.0-sofia
     */
    private $selects;

    /**
     * Flag whether the values returned must be distinct or not
     * @var boolean
     */
    private $distinct = false;

    /**
     * The list of joins
     * @var ArrayList
     * @since 1.0-sofia
     */
    private $joins;

    /**
     * The where condition
     * @var string
     * @since 1.0-sofia
     */
    private $where;

    /**
     * The list of groupings
     * @var ArrayList
     * @since 1.0-sofia
     */
    private $groupings;

    /**
     * The list of orderings
     * @var ArrayList
     * @since 1.0-sofia
     */
    private $orderings;

    /**
     * The maximum number of rows to fetch
     * @var integer
     * @since 1.0-sofia
     */
    private $limit;

    /**
     * The offset to start fetching from
     * @var integer
     * @since 1.0-sofia
     */
    private $offset = 0;
    
    /**
     * Flags if the result should be reversed.
     * @var boolean 
     * @since 1.0-sofia
     */
    private $reverse = false;
    
    /**
     * The mapping function that will map the columns
     * @var Closure|callback
     * @since 1.0-sofia 
     */
    private $mapping;
    
    /**
     * The parameters set to the query
     * @var Map
     * @since 1.0-sofia
     */
    private $params;
    
    /**
     * Create a new Linq object
     * @param IConnector $driver The connector to connect
     * @param string $source The name of the table
     * @since 1.0-sofia
     */
    public function __construct($driver, $source){
        parent::__construct($driver, $source);
        $this->reset();
    }
    
    /**
     * Get the resulting query string
     * @return string Returns the resulting query string
     * @since 1.0-sofia
     */
    public function query(){
        $query = 'SELECT ';
        if($this->distinct){
            $query .= 'DISTINCT ';
        }
        if(count($this->selects) === 0){
            $query .= '* ';
        }else{
            $query .= implode(', ', $this->selects->toArray()) . ' ';
        }
        $query .= 'FROM ' . $this->name . ' ';
        if($this->joins->count() > 0){
            foreach($this->joins as $join){
                $query .= $join->create() . ' ';
            }
        }
        if($this->where){
            $query .= 'WHERE ' . $this->where . ' ';
        }
        if($this->groupings->count() > 0){
            $query .= 'GROUP BY ' . implode(', ', $this->groupings->toArray()) . ' ';
        }
        if($this->orderings->count() > 0){
            $query .= 'ORDER BY ';
            $orders = array();
            foreach($this->orderings as $order){
                $orders[] = $order->create();
            }
            $query .= implode(', ', $orders) . ' ';
        }
        if($this->limit !== null && $this->offset !== null){
            $query .= 'LIMIT ' . $this->offset . ', ' . $this->limit . ' ';
        }elseif($this->limit !== null){
            $query .= 'LIMIT ' . $this->limit . ' ';
        }
        return rtrim($query);
    }
    
    /**
     * Reset everything
     * @since 1.0-sofia 
     */
    protected function reset(){
        $this->distinct = false;
        $this->groupings = new ArrayList();
        $this->joins = new ArrayList();
        $this->limit = null;
        $this->offset = 0;
        $this->orderings = new ArrayList();
        $this->reverse = false;
        $this->selects = new ArrayList();
        $this->where = null;
        $this->mapping = null;
        $this->params = new Map();
    }
    
    /**
     * Fetch the result 
     * @param array|Map $params (optional) The parameter values to be set
     * @return ArrayList Returns the list of result fetched.
     * @since 1.0-sofia
     */
    public function fetch($params = array()){
        if(func_num_args() == 1){
            $this->params($params);
        }
        $statement = $this->prepare();
        $statement->execute();
        $list = $statement->fetchAll(\PDO::FETCH_NUM);
        if($this->reverse){
            $list = array_reverse($list);
        }
        if($this->mapping){
            $map = $this->mapping;
            $result = array();
            foreach($list as $index => $row){
                $row = $map($row);
                $result[$index] = $row;
            }
            $list = $result;
        }
        $this->reset();
        return new ArrayList($list);
    }
    
    /**
     * Prepare the statement and bind parameters
     * @return \PDOStatement Returns the statement prepared.
     * @since 1.0-sofia 
     */
    private function prepare(){
        $query = $this->query();
        $statement = $this->driver->prepare($query);
        foreach($this->params as $key => &$value){
            $statement->bindParam($key, $value);
        }
        return $statement;
    }
    
    /**
     * Set the method to walk through the rows to map the columns to properties
     * @param Closure|callback $selector Set the selector to perform mapping
     * @returns Linq Returns the LINQ object for chaining
     * @since 1.0-sofia
     */
    public function map($selector){
        $this->mapping = $selector;
        return $this;
    }
    
    /**
     * Set the mapping of each row to be an instance of an object
     * 
     * The row data will be passed into a new instance of the object
     * by the constructor arguments.
     * 
     * @param string|object $object The class name or object instance to map to
     * @returns Linq Returns the LINQ object for chaining
     * @since 1.1-sofia
     */
    public function listOf($object){
        $this->map(function($row) use ($object){
            $reflection = new \ReflectionClass($object);
            return call_user_func_array(array($reflection, 'newInstance'), $row);
        });
        return $this;
    }
    
    /**
     * Set a parameter to be binded to the query
     * @param string $key The name of the parameter
     * @param mixed $value The value of the parameter 
     * @returns Linq Returns the LINQ object for chaining
     * @since 1.0-sofia
     */
    public function param($key, $value){
        $this->params[$key] = $value;
        return $this;
    }
    
    /**
     * Add an array of parameters to the query
     * @param Map|array $params The parameters to be binded
     * @returns Linq Returns the LINQ object for chaining
     * @since 1.0-sofia
     */
    public function params($params = null){
        $this->params = new Map($params);
        return $this;
    }
    
    /**
     * Start the LINQ statement
     * @param string $source The name of the table to fetch from
     * @param IConnector $driver The connector to connect
     * @return Linq Returns the new LINQ object
     * @since 1.0-sofia
     */
    public static function from($source, $driver = null){
        return new self($driver, $source);
    }
    
    /**
     * Check if all the records satisfy the given condition
     * @param string $predicate The given condition
     * @return boolean Returns true if one or more of the records satisfy the
     *                 condition, false otherwise. 
     * @since 1.0-sofia
     */
    public function all($predicate) {
        $count = $this->count('');
        if($count == 0){
            return true;
        }
        $condCount = $this->count($predicate);
        return $condCount === $count;
    }

    /**
     * Check if any of the records satisfy the given condition.
     * @param string $predicate The given condition
     * @return boolean Returns true if one or more of the records satisfy the
     *                 condition, false otherwise. 
     * @since 1.0-sofia
     */
    public function any($predicate = null) {
        $count = $this->count('');
        if(!$predicate && $count > 0){
            return true;
        }
        $condCount = $this->count($predicate);
        return $condCount > 0;
    }

    /**
     * Get the average
     * @param string $field The field to calculate the average
     * @since 1.0-sofia
     */
    public function average($field = null) {
        if(!$field){
            $field = '*';
        }
        $this->selects = new ArrayList(array(
            'AVERAGE(' . $field . ')'
        ));
        $statement = $this->prepare();
        $statement->execute();
        $this->reset();
        return reset($statement->fetchAll(\PDO::FETCH_COLUMN));
    }

    /**
     * Count the number of rows based on the conditions
     * @param string $condition The condition. 
     * @return integer Returns the number of rows
     * @since 1.0-sofia
     */
    public function count($condition = null) {
        $this->select('COUNT(*)');
        if($condition){
            $this->where($condition);
        }
        $statement = $this->prepare();
        $statement->execute();
        $this->reset();
        $data = $statement->fetchAll(\PDO::FETCH_COLUMN);
        return reset($data);
    }

    /**
     * Set the result to be distinct
     * @param boolean $distinct (optional) Set whether to be distinct or not
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia 
     */
    public function distinct($distinct = true) {
        $this->distinct = $distinct;
        return $this;
    }

    /**
     * Get the first record
     * If no record exists, a NullException will be thrown
     * @param string $predicate The condition to get the last row
     * @return mixed Returns the record
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
     * Get the last record
     * @param string $predicate The condition to get the last row
     * @return mixed Returns the record or NULL if no record exists
     * @since 1.0-sofia
     */
    public function firstOrDefault($predicate = null) {
        $this->offset = 0;
        $this->limit = 1;
        if($predicate){
            $this->where($predicate);
        }
        
        $list = $this->fetch();
        if($list->count() == 0){
            return null;
        }
        return $list->first();
    }

    /**
     * Group the results by a field
     * @param string $field The field to group by
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function groupBy($field) {
        $this->groupings->add($field);
        return $this;
    }

    /**
     * Add a join relationship to another table
     * @param string $source The name of the other table
     * @param string $innerKey The key that matches the outer key
     * @param string $outerKey The outer key
     * @param string $selector The type of join (LEFT, RIGHT, OUTER, INNER)
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function join($source, $innerKey, $outerKey, $selector = '') {
        $this->joins->add(new LinqJoin($this->name, $source, $innerKey, $outerKey, $selector));
        return $this;
    }

    /**
     * Get the last record
     * If no record exists, a NullException will be thrown
     * @param string $predicate The condition to get the last row
     * @return mixed Returns the record
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
     * Get the last record
     * @param string $predicate The condition to get the last row
     * @return mixed Returns the record or NULL if no record exists
     * @since 1.0-sofia
     */
    public function lastOrDefault($predicate = null) {
        if($predicate){
            $this->where($predicate);
        }
        
        $list = $this->fetch();
        if($list->count() == 0){
            return null;
        }
        return $list->last();
    }

    /**
     * Set the offset and limit to the query
     * @param integer $offset The offset to start from
     * @param integer $length The number of rows to fetch
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function limit($offset, $length = null) {
        $this->offset = $offset;
        if($length !== null){
            $this->limit = $length;
        }
        return $this;
    }

    /**
     * Get the maximum value for a column
     * @param string $field The column to calculate the maximum value
     * @return ArrayList Returns a list of minimum values
     * @since 1.0-sofia
     */
    public function max($field = null) {
        $this->selects = new ArrayList(array(
            'MAX(' . $field . ')'
        ));
        $statement = $this->prepare();
        $statement->execute();
        $this->reset();
        return new ArrayList($statement->fetchAll(\PDO::FETCH_COLUMN));
    }

    /**
     * Get the minimum value of a column
     * @param string $field (optional) The column to fetch
     * @return ArrayList Returns a list of minimum values
     * @since 1.0-sofia
     */
    public function min($field = null) {
        $this->selects = new ArrayList(array(
            'MIN(' . $field . ')'
        ));
        $statement = $this->prepare();
        $statement->execute();
        $this->reset();
        return new ArrayList($statement->fetchAll(\PDO::FETCH_COLUMN));
    }

    /**
     * Set the order of fetching the result in an ascending order
     * @param string $field The field to sort
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function orderBy($field) {
        $this->orderings->add(new LinqOrder($field));
        return $this;
    }

    /**
     * Set the order of fetching the result in a descending order
     * @param string $field The field to sort
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function orderByDesc($field) {
        $this->orderings->add(new LinqOrder($field, true));
        return $this;
    }

    /**
     * Reverse the resultset
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function reverse() {
        $this->reverse = !$this->reverse;
        return $this;
    }

    /**
     * Select what fields to fetch
     * If more than one parameter is entered, the arguments will become the
     * fields to fetch.
     * @param IList|array|mixed $mapper The field(s) to fetch
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function select($mapper) {
        if(func_num_args() > 1){
            $this->selects = new ArrayList(func_get_args());
        }else{
            $this->selects->append($mapper);
        }
        return $this;
    }

    /**
     * Set the offset 
     * @param string $count The amount of rows to skip
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function skip($count) {
        $this->offset = $count;
        return $this;
    }

    /**
     * Get the sum of a column
     * @param string $field (optional) The field to calculate the sum
     * @return ArrayList Returns a list of sum values
     * @since 1.0-sofia
     */
    public function sum($field = null) {
        $this->selects = new ArrayList(array(
            'SUM(' . $field . ')'
        ));
        $statement = $this->prepare();
        $statement->execute();
        $this->reset();
        return new ArrayList($statement->fetchAll(\PDO::FETCH_COLUMN));
    }

    /**
     * Set the amount of rows to fetch
     * @param integer $count The number of rows to fetch
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function take($count) {
        $this->limit = $count;
        return $this;
    }

    /**
     * Set the conditions
     * @param string $condition The conditions
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function where($condition) {
        $this->where = $condition;
        return $this;
    }

    /**
     * Set the order of fetching the result in an ascending order
     * @param string $field The field to sort
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function thenBy($field) {
        $this->orderings->add(new LinqOrder($field));
        return $this;
    }

    /**
     * Set the order of fetching the result in a descending order
     * @param string $field The field to sort
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function thenByDesc($field) {
        $this->orderings->add(new LinqOrder($field, true));
        return $this;
    }

    /**
     * Set the model for the LINQ query
     * @param Model|array|ArrayList $model The model or collection of models to fetch
     * @return Linq Returns the Linq object for chaining
     * @since 1.0-sofia
     */
    public function model($model) {
        if(func_num_args() == 1){
            if(is_array($model) || $model instanceof ArrayList){
                $args = $model;
            }else{
                $args = array($model);
            }
        }else{
            $args = func_get_args();
        }
        $mapping = array();
        foreach($args as $name => $arg){
            /* @var $arg Model */
            $columns = new ArrayList();
            $properties = new ArrayList();
            $class = get_class($arg);
            
            $map = array();
            $table = '';
            if($arg instanceof IModel){
                $map = $arg->map();
                if($arg->dbName()){
                    $table = $arg->dbName() . '.';
                }
            }else{
                $props = get_object_vars($arg);
                foreach($props as $key => $value){
                    $map[$key] = $key;
                }
            }
            
            foreach($map as $column => $property){
                if(is_array($property)){
                    foreach($property as $key => $value){
                        $columns[] = $table . $key;
                        $properties[] = $value;
                    }
                }else{
                    $columns[] = $table . $column;
                    $properties[] = $property;
                }
            }
            $mapping[] = array(
                'name' => $name,
                'class' => $class,
                'columns' => $columns,
                'properties' => $properties
            );
        }
        $columns = new ArrayList();
        foreach($mapping as $instance){
            $columns->append($instance['columns']);
        }
        $this->select($columns);
        $this->mapping = function($row)
                use ($class, $mapping){
            $result = array();
            $columnIndex = 0;
            foreach($mapping as $instance){
                $class = $instance['class'];
                $obj = new $class();
                foreach($instance['properties'] as $property){
                    $obj->$property = $row[$columnIndex];
                    ++$columnIndex;
                }
                $name = $class;
                if(is_string($instance['name'])){
                    $name = $instance['name'];
                }
                $result[$name] = $obj;
            }
            if(count($result) == 1){
                $result = reset($result);
            }
            return $result;
        };
        return $this;
    }
    
}