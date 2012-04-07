<?php

/**
 * A sorter that sorts a sortable.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection.sort
 * @since 1.0-sofia
 */
interface ISorter {
    
    /**
     * Perform the sorting operation
     * @param ISortable $sortable The sortable object
     * @since 1.0-sofia
     */
    public function sort($sortable);
    
}