<?php
namespace Packfire\Linq;

use Packfire\Linq\Linq;
use Packfire\Linq\IOrderedLinq;
use Packfire\Linq\LinqThenByQuery;

/**
 * OrderedLinq class
 * 
 * An ordered LINQ that implements the thenBy() and thenByDesc() methods.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
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
       $this->queueAdd(new LinqThenByQuery($field, array($lastQuery, 'compare')));
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
       $this->queueAdd(new LinqThenByQuery($field, array($lastQuery, 'compare'), true));
       return $this;
    }
    
}