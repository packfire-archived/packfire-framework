<?php
namespace Packfire\Linq;

use ILinqWorkerQuery;
pload('ILinqWorkerQuery');

/**
 * LinqWorkerQuery class
 * 
 * A LINQ Worker Query Abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq
 * @since 1.0-sofia
 */
abstract class LinqWorkerQuery implements ILinqWorkerQuery {
    
    /**
     * The worker
     * @var Closure|callback
     * @since 1.0-sofia
     */
    private $worker;
    
    /**
     * Create a new LinqWorkerQuery object
     * @param Closure|callback $worker The worker callback or closure for this query.
     * @since 1.0-sofia 
     */
    public function __construct($worker){
        $this->worker = $worker;
    }
    
    /**
     * Get the worker working for this query
     * @return Closure|callback Returns the registered worker
     * @since 1.0-sofia 
     */
    public function worker(){
        return $this->worker;
    }
    
}