<?php
pload('packfire.plinq.IOrderedLinq');
pload('packfire.database.drivers.mysql.pMySqlTable');
pload('packfire.collection.pList');
pload('pMySqlLinqJoin');
pload('pMySqlLinqOrder');

/**
 * Provides LINQ functionality to a MySQL table on top of the existing MySQL
 * table functionalities.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.divers.mysql.linq
 * @since 1.0-sofia
 */
class pMySqlLinq extends pMySqlTable implements IOrderedLinq {

    /**
     * The list of selects
     * @var pList
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
     * @var pList
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
     * @var pList
     * @since 1.0-sofia
     */
    private $groupings;

    /**
     * The list of orderings
     * @var pList
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
     * @var type 
     * @since 1.0-sofia
     */
    private $reverse = false;
    
    /**
     * Create a new pMySqlLinq
     * @param pDbConnector $driver The connector to connect
     * @param string $source The name of the table
     * @since 1.0-sofia
     */
    public function __construct($driver, $source){
        parent::__construct($driver, $source);
        $this->selects = new pList();
        $this->groupings = new pList();
        $this->orderings = new pList();
        $this->joins = new pList();
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
            foreach($this->orderings as $order){
                $query .= $order->create() . ' ';
            }
        }
        if($this->limit !== null && $this->offset !== null){
            $query .= 'LIMIT ' . $this->offset . ', ' . $this->limit . ' ';
        }elseif($this->limit !== null){
            $query .= 'LIMIT ' . $this->limit . ' ';
        }
        return rtrim($query);
    }
    
    /**
     * Fetch the result 
     * @return pList Returns the list of result fetched.
     * @since 1.0-sofia
     */
    public function fetch(){
        $query = $this->query();
        $statement = $this->driver->prepare($query);
        $list = $statement->fetchAll(PDO::FETCH_NUM);
        if($this->reverse){
            $list = array_reverse($list);
        }
        return new pList($list);
    }
    
    /**
     * Start the LINQ statement
     * @param string $source The name of the table to fetch from
     * @param pDbConnector $driver The connector to connect
     * @return pMySqlLinq
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
    public function average($field) {
        $this->selects = new pList(array(
            'AVERAGE(' . $field . ')'
        ));
        $query = $this->query();
        $statement = $this->driver->query($query);
        return new pList($statement->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Count the number of rows based on the conditions
     * @param string $condition The condition. 
     * @return integer Returns the number of rows
     * @since 1.0-sofia
     */
    public function count($condition = null) {
        $this->selects = new pList(array(
            'COUNT(*)'
        ));
        if($condition){
            $this->where($condition);
        }
        $query = $this->query();
        $statement = $this->driver->query($query);
        return new pList($statement->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Set the result to be distinct
     * @param boolean $distinct (optional) Set whether to be distinct or not
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia 
     */
    public function distinct($distinct = true) {
        $this->distinct = $distinct;
        return $this;
    }

    /**
     * Get the first record
     * If no record exists, a pNullException will be thrown
     * @param string $predicate The condition to get the last row
     * @return mixed Returns the record
     * @since 1.0-sofia
     */
    public function first($predicate = null) {
        $result = $this->firstOrDefault($predicate);
        if($result === null){
            throw new pNullException(''); // TODO null exception message
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
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
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
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function join($source, $innerKey, $outerKey, $selector = '') {
        $this->joins->add(new pMySqlLinqJoin($this->name, $source, $innerKey, $outerKey, $selector));
        return $this;
    }

    /**
     * Get the last record
     * If no record exists, a pNullException will be thrown
     * @param string $predicate The condition to get the last row
     * @return mixed Returns the record
     * @since 1.0-sofia
     */
    public function last($predicate = null) {
        $result = $this->lastOrDefault($predicate);
        if($result === null){
            throw new pNullException(''); // TODO null exception message
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
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
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
     * @return pList Returns a list of minimum values
     * @since 1.0-sofia
     */
    public function max($field = null) {
        $this->selects = new pList(array(
            'MAX(' . $field . ')'
        ));
        $query = $this->query();
        $statement = $this->driver->query($query);
        return new pList($statement->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Get the minimum value of a column
     * @param string $field (optional) The column to fetch
     * @return pList Returns a list of minimum values
     * @since 1.0-sofia
     */
    public function min($field = null) {
        $this->selects = new pList(array(
            'MIN(' . $field . ')'
        ));
        $query = $this->query();
        $statement = $this->driver->query($query);
        return new pList($statement->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Set the order of fetching the result in an ascending order
     * @param string $field The field to sort
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function orderBy($field) {
        $this->orderings->add(new pMySqlLinqOrder($field));
        return $this;
    }

    /**
     * Set the order of fetching the result in a descending order
     * @param string $field The field to sort
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function orderByDesc($field) {
        $this->orderings->add(new pMySqlLinqOrder($field, true));
        return $this;
    }

    /**
     * Reverse the resultset
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
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
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function select($mapper) {
        if(func_num_args() > 1){
            $this->selects = new pList(func_get_args());
        }else{
            if(is_array($mapper) || $mapper instanceof IList){
                $this->selects = $mapper;
            }else{
                $this->selects = new pList(array($mapper));
            }
        }
        return $this;
    }

    /**
     * Set the offset 
     * @param string $count The amount of rows to skip
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function skip($count) {
        $this->offset = $count;
        return $this;
    }

    /**
     * Get the sum of a column
     * @param string $field (optional) The field to calculate the sum
     * @return pList Returns a list of sum values
     * @since 1.0-sofia
     */
    public function sum($field = null) {
        $this->selects = new pList(array(
            'SUM(' . $field . ')'
        ));
        $query = $this->query();
        $statement = $this->driver->query($query);
        return new pList($statement->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Set the amount of rows to fetch
     * @param integer $count The number of rows to fetch
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function take($count) {
        $this->limit = $count;
        return $this;
    }

    /**
     * Set the conditions
     * @param string $condition The conditions
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function where($condition) {
        $this->where = $condition;
        return $this;
    }

    /**
     * Set the order of fetching the result in an ascending order
     * @param string $field The field to sort
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function thenBy($field) {
        $this->orderings->add(new pMySqlLinqOrder($field));
        return $this;
    }

    /**
     * Set the order of fetching the result in a descending order
     * @param string $field The field to sort
     * @return pMySqlLinq Returns the pMySqlLinq object for chaining
     * @since 1.0-sofia
     */
    public function thenByDesc($field) {
        $this->orderings->add(new pMySqlLinqOrder($field, true));
        return $this;
    }
    
}