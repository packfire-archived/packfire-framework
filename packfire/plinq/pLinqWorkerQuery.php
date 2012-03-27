<?php
pload('ILinqWorkerQuery');


/**
 * A LINQ Worker Query Abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
abstract class pLinqWorkerQuery implements ILinqWorkerQuery {
    
    /**
     * The worker
     * @var Closure|callback
     * @since 1.0-sofia
     */
    private $worker;
    
    /**
     * Create a new Worker Query pLinqWorkerQuery object
     * @param Closure|callback $worker The worker callback or closure for this query.
     * @since 1.0-sofia 
     */
    public function __construct($worker){
        $this->worker = $worker;
    }
    
    /**
     * Get the worker
     * @return Closure|callback Returns the registered worker
     * @since 1.0-sofia 
     */
    public function worker(){
        return $this->worker;
    }
    
}