<?php
namespace Packfire\Collection\Sort;

use Packfire\Collection\Sort\IComparator;

/**
 * ISorter interface
 * 
 * A sorter that sorts a something sortable.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection\Sort
 * @since 1.0-sofia
 */
interface ISorter extends IComparator {
    
    /**
     * Perform the sorting operation
     * @param mixed $sortable Something sortable
     * @since 1.0-sofia
     */
    public function sort(&$sortable);
    
}