<?php

/**
 * Sorter
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection.sort
 * @since 1.0-sofia
 */
interface ISorter {
    
    /**
     * Perform the sorting operation
     * @params ISortable 
     */
    public function sort($sortable);
    
}