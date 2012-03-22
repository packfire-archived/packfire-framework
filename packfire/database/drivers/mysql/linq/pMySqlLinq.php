<?php
pload('packfire.database.pDbLinq');
pload('packfire.collection.pList');
pload('pMySqlLinqJoin');
pload('pMySqlLinqOrder');

/**
 * pMySqlLinq Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.divers.mysql.linq
 * @since 1.0-sofia
 */
class pMySqlLinq extends pDbLinq {

    /**
     *
     * @var pList
     */
    private $selects;

    private $aggregate;

    private $distinct = false;

    private $from;

    /**
     *
     * @var pList
     */
    private $joins;

    private $where;

    /**
     *
     * @var pList
     */
    private $groupings;

    /**
     *
     * @var pList
     */
    private $orderings;

    private $limit;

    private $offset = 0;
    
    private $reverse = false;
    
    public function __construct($source){
        parent::__construct(null);
        $this->from = $source;
        $this->selects = new pList();
        $this->groupings = new pList();
        $this->orderings = new pList();
        $this->joins = new pList();
    }
    
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
        $query .= 'FROM ' . $this->from . ' ';
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
    
    public static function from($source){
        return new self($source);
    }
    
    public function all($predicate) {
        
    }

    public function any($predicate = null) {
        
    }

    public function average($field) {
        $this->selects = new pList(array(
            'AVERAGE(' . $field . ')'
        ));
    }

    public function count($condition = null) {
        $this->selects = new pList(array(
            'COUNT(*)'
        ));
        $this->where($condition);
    }

    public function distinct() {
        $this->distinct = true;
    }

    public function first($predicate = null) {
        $this->offset = 0;
        $this->limit = 1;
    }

    public function firstOrDefault($predicate = null) {
        $this->offset = 0;
        $this->limit = 1;
    }

    public function groupBy($field) {
        $this->groupings->add($field);
        return $this;
    }

    public function join($source, $innerKey, $outerKey, $selector = '') {
        $this->joins->add(new pMySqlLinqJoin($this->from, $source, $innerKey, $outerKey, $selector));
        return $this;
    }

    public function last($predicate = null) {
        
    }

    public function lastOrDefault($predicate = null) {
        
    }

    public function limit($offset, $length = null) {
        $this->offset = $offset;
        if($length !== null){
            $this->limit = $length;
        }
        return $this;
    }

    public function max($field = null) {
        $this->selects = new pList(array(
            'MAX(' . $field . ')'
        ));
        
    }

    public function min($field = null) {
        $this->selects = new pList(array(
            'MIN(' . $field . ')'
        ));
        
    }

    public function orderBy($field) {
        $this->orderings->add(new pMySqlLinqOrder($field));
        return $this;
    }

    public function orderByDesc($field) {
        $this->orderings->add(new pMySqlLinqOrder($field, true));
        return $this;
    }

    public function reverse() {
        $this->reverse = !$this->reverse;
        return $this;
    }

    public function select($mapper) {
        if(func_num_args() > 1){
            $this->selects = new pList(func_get_args());
        }else{
            if(is_array($mapper) || $mapper instanceof pList){
                $this->selects = $mapper;
            }else{
                $this->selects = new pList(array($mapper));
            }
        }
        return $this;
    }

    public function skip($count) {
        $this->offset = $count;
        return $this;
    }

    public function sum($field = null) {
        $this->selects = new pList(array(
            'MIN(' . $field . ')'
        ));
    }

    public function take($count) {
        $this->limit = $count;
        return $this;
    }

    public function where($condition) {
        $this->where = $condition;
        return $this;
    }

    public function thenBy($field) {
        $this->orderings->add(new pMySqlLinqOrder($field));
    }

    public function thenByDesc($field) {
        $this->orderings->add(new pMySqlLinqOrder($field, true));
    }
    
}