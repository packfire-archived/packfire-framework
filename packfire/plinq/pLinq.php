<?php
pload('ILinq');
pload('packfire.collection.pList');
pload('pLinqWhereQuery');
pload('pLinqTakeQuery');
pload('pLinqSkipQuery');
pload('pLinqSelectQuery');
pload('pLinqDistinctQuery');
pload('pLinqReverseQuery');
pload('pLinqOrderByQuery');

class pLinq implements ILinq, IteratorAggregate, Countable {
    
    /**
     *
     * @var pList
     */
    private $queue;
    
    /**
     *
     * @var pList
     */
    private $collection;
    
    public function __construct($collection, $queries = null){
        if($queries){
            $this->queue = $queries;
        }else{
            $this->queue = new pList();
        }
        if(is_array($collection)){
            $collection = new pList($collection);
        }
        $this->collection = $collection;
    }
    
    public static function from($source, $queries = null){
        return new self($source, $queries);
    }
    
    protected function orDowncast(){
        if(get_class($this) !== __CLASS__){
            return new self($this->collection, $this->queue);
        }
        return $this;
    }
    
    protected function lastQuery(){
        return empty($this->queue) ? null : end($this->queue);
    }
    
    protected function finalize(){
        $collection = $this->collection->toArray();
        foreach($this->queue as $query){
            $collection = $query->run($collection);
        }
        return new pList($collection);
    }
    
    protected function queueAdd($query){
        $this->queue->add($query);
    }
    
    public function average($field) {
        $result = self::from($this->collection, $this->queue)->select($field)->finalize();
        return array_sum($result) / count($result);
    }

    public function count($condition = null) {
        $copy = self::from($this->collection, $this->queue);
        if($condition){
            $copy->where($condition);
        }
        return $copy->finalize()->count();
    }

    public function distinct() {
        $this->queueAdd(new pLinqDistinctQuery());
        return $this->orDowncast();
    }

    public function first($predicate = null) {
        $result = $this->firstOrDefault($predicate);
        if($result === null){
            throw new pNullException('');  // TODO complete exception
        }
        return $result;
    }
    
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

    public function groupBy($field) {
        $this->queueAdd(new pLinqGroupByQuery($field));
        return $this->orDowncast();
    }

    public function join($subject, $name, $conditionFunc) {
        
    }

    public function last($predicate = null) {
        $result = $this->lastOrDefault($predicate);
        if($result === null){
            throw new pNullException(''); // TODO complete exception
        }
        return $result;
    }

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

    public function limit($offset, $length = null) {
        $major = $this->skip($offset);
        if($length){
            $major = $major->take($length);
        }
        return $major;
    }

    public function max($field) {
        return self::from($this->finalize())->orderBy($field)->lastOrDefault();
    }

    public function min($field) {
        return self::from($this->finalize())->orderBy($field)->firstOrDefault();
    }
    
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

    public function orderBy($field) {
        $this->queueAdd(new pLinqOrderByQuery($field));
        return new pOrderedLinq($this->collection, $this->queue);
    }

    public function orderByDesc($field) {
        $this->queueAdd(new pLinqOrderByQuery($field, true));
        return new pOrderedLinq($this->collection, $this->queue);
    }

    public function select($mapper) {
        $this->queueAdd(new pLinqSelectQuery($mapper));
        return $this->orDowncast();
    }

    public function sum($field = null) {
        $copy = new self($this->finalize());
        $result = $copy->select($field)->finalize();
        return array_sum($result);
    }
    
    public function skip($count){
        $this->queueAdd(new pLinqSkipQuery($count));
        return $this->orDowncast();
    }
    
    public function take($count){
        $this->queueAdd(new pLinqTakeQuery($count));
        return $this->orDowncast();
    }

    public function where($condition) {
        $this->queueAdd(new pLinqWhereQuery($condition));
        return $this->orDowncast();
    }
    
    public function getIterator() {
        return new ArrayIterator($this->collection);
    }
    
    /**
     *
     * @return pList
     */
    public function toList(){
        return $this->finalize();
    }

    public function reverse() {
        $this->queueAdd(new pLinqReverseQuery());
        return $this->orDowncast();
    }
    
}