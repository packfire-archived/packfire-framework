<?php
pload('pLinq');
pload('IOrderedLinq');
pload('pLinqThenByQuery');

/**
 * An ordered LINQ that implements the thenBy() and thenByDesc() methods.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
class pOrderedLinq extends pLinq implements IOrderedLinq {
    
    /**
     * Perform a thenBy sort after the previous sort command
     * @param Closure|callback $field The field selector
     * @return pOrderedLinq Returns itself for chaining.
     * @since 1.0-sofia
     */
    public function thenBy($field) {
       $lastQuery = $this->lastQuery();
       $this->queueAdd(new pLinqThenByQuery($field, array($lastQuery, 'compare')));
       return $this;
    }

    /**
     * Perform a thenBy sort after the previous sort command
     *           in an descending order
     * @param Closure|callback $field The field selector
     * @return pOrderedLinq Returns itself for chaining.
     * @since 1.0-sofia
     */
    public function thenByDesc($field) {
       $lastQuery = $this->lastQuery();
       $this->queueAdd(new pLinqThenByQuery($field, array($lastQuery, 'compare'), true));
       return $this;
    }
    
}