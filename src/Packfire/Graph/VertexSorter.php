<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Graph;

use Packfire\Collection\Sort\ISorter;

/**
 * Provides a comparator that helps to sort an array of vertices
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Graph
 * @since 1.0-sofia
 */
class VertexSorter implements ISorter
{
    /**
     * Perform the sorting operation
     * @param mixed $list The list of vertices to sort
     * @since 1.0-sofia
     */
    public function sort(&$list)
    {
        uasort($list, array($this, 'compare'));
    }

    /**
     * Compare two IVertex objects in terms of their
     * @param  IVertex $a The first vertex
     * @param  IVertex $b The second vertex
     * @return integer Returns the comparison value
     * @since 1.0-sofia
     */
    public function compare($a, $b)
    {
        $noHasA = $a->potential() === null;
        $noHasB = $b->potential() === null;
        if ($noHasA || $noHasB) {
            if (!$noHasA) {
                return -1;
            }
            if (!$noHasB) {
                return 1;
            }

            return 0;
        } else {
            return $a->potential() < $b->potential() ? -1 : 1;
        }
    }

}
