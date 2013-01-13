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

/**
 * An interface that allows comparing of two objects.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection\Sort
 * @since 1.0-sofia
 */
interface IComparator {
    
    /**
     * Compare between two items
     * @param mixed $one The first item
     * @param mixed $two The second item
     * @returns integer Returns -1, 0 or 1 for sorting
     * @since 1.0-sofia
     */
    public function compare($one, $two);
    
}