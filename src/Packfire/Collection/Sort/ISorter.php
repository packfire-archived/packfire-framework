<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Collection\Sort;

use Packfire\Collection\Sort\IComparator;

/**
 * A sorter that sorts a something sortable.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
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