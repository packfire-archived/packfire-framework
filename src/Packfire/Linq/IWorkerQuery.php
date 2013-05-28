<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Linq;

/**
 * A worker query that allows the specification of a worker closure or callback.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
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
