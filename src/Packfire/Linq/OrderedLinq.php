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

use Packfire\Linq\Linq;
use Packfire\Linq\IOrderedLinq;
use Packfire\Linq\Query\ThenBy;

/**
 * An ordered LINQ that implements the thenBy() and thenByDesc() methods.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq
 * @since 1.0-sofia
 */
class OrderedLinq extends Linq implements IOrderedLinq {

    /**
     * Perform a thenBy sort after the previous sort command
     * @param Closure|callback $field The field selector
     * @return OrderedLinq Returns itself for chaining.
     * @since 1.0-sofia
     */
    public function thenBy($field) {
       $lastQuery = $this->lastQuery();
       $this->queueAdd(new ThenBy($field, array($lastQuery, 'compare')));
       return $this;
    }

    /**
     * Perform a thenBy sort after the previous sort command
     *           in an descending order
     * @param Closure|callback $field The field selector
     * @return OrderedLinq Returns itself for chaining.
     * @since 1.0-sofia
     */
    public function thenByDesc($field) {
       $lastQuery = $this->lastQuery();
       $this->queueAdd(new ThenBy($field, array($lastQuery, 'compare'), true));
       return $this;
    }

}