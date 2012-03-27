<?php
pload('pLinqWorkerQuery');
pload('packfire.collection.pList');
pload('packfire.collection.IComparator');

/**
 * A LINQ Order By Query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.linq
 * @since 1.0-sofia
 */
class pLinqOrderByQuery extends pLinqWorkerQuery implements IComparator {
    
    protected $descending;
    
    public function __construct($worker, $descending = false){
        parent::__construct($worker);
        $this->descending = $descending;
    }
    
    public function run($collection) {
        usort($collection, array($this, 'compare'));
        return $collection;
    }
    
    public function compare($a, $b){
        if($this->descending){
            $direction = -1;
        }else{
            $direction = 1;
        }
        $worker = $this->worker();
        if($worker){
            $a = $worker($a);
            $b = $worker($b);
        }
        
        if($a === $b){
            return 0;
        }
        return $a < $b ? -1 * $direction : $direction;
    }
    
}