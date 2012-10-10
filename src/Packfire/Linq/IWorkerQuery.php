<?php
namespace Packfire\Linq;

use Packfire\Linq\IQuery;

/**
 * IWorkerQuery interface
 * 
 * A worker query that allows the specification of a worker closure or callback.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq
 * @since 1.0-sofia
 */
interface IWorkerQuery extends IQuery {
    
    /**
     * Get the worker for the query
     * @return Closure|callback
     * @since 1.0-sofia 
     */
    public function worker();
    
}
