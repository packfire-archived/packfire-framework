<?php
namespace Packfire\Linq;

use LinqWorkerQuery;
use Packfire\Collection\Sort\IComparator;

/**
 * LinqOrderByQuery class
 * 
 * A LINQ Order By Query
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq
 * @since 1.0-sofia
 */
class LinqOrderByQuery extends LinqWorkerQuery implements IComparator {
    
    /**
     * Flag whether the order is descending or not
     * @var boolean
     * @since 1.0-sofia
     */
    protected $descending;
    
    /**
     * Create a new LinqOrderByQuery object
     * @param Closure|callback $worker The callback that will work on this query
     * @param boolean $descending Set if the order is in descending or not.
     *                  True if the order is descending, false otherwise.
     *                  Defaults to false.
     * @since 1.0-sofia
     */
    public function __construct($worker, $descending = false){
        parent::__construct($worker);
        $this->descending = $descending;
    }
    
    /**
     * Execute the query
     * @param array $collection The collection to execute upon
     * @return mixed Returns the result after the query execution
     * @since 1.0-sofia
     */
    public function run($collection) {
        usort($collection, array($this, 'compare'));
        return $collection;
    }
    
    /**
     * The comparison method
     * @param mixed $a The first item to compare
     * @param mixed $b The second item to compare
     * @return integer Returns the comparison result -1, 0 or 1.
     * @since 1.0-sofia
     * @internal
     */
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