<?php
pload('pLinq');
pload('IOrderedLinq');
pload('pLinqThenByQuery');

/**
 * pOrderedLinq Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since version-created
 */
class pOrderedLinq extends pLinq implements IOrderedLinq {
    
    public function thenBy($field) {
       $lastQuery = $this->lastQuery();
       $this->queueAdd(new pLinqThenByQuery($field, array($lastQuery, 'compare')));
    }

    public function thenByDesc($field) {
       $lastQuery = $this->lastQuery();
       $this->queueAdd(new pLinqThenByQuery($field, array($lastQuery, 'compare'), true));
    }
    
}