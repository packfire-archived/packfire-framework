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

/**
 * A solved path between the start and end vertex on a graph
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Graph
 * @since 1.0
 */
class Path
{
    /**
     * The list of vertices in the path
     * @var ArrayList
     * @since 1.0-sofia
     */
    private $vertices;

    /**
     * Create a new Path object
     * @param ArrayList $vertices Set the vertices in the path
     * @since 1.0-sofia
     */
    public function __construct($vertices)
    {
        $this->vertices = $vertices;
    }

    /**
     * Get the list of vertices in the path
     * @return ArrayList Returns the list of vertices in the path
     * @since 1.0-sofia
     */
    public function vertices()
    {
        return $this->vertices;
    }

    /**
     * Get the distance from the starting vertex up to the specified vertex, or
     * the total distance if no vertex is specified.
     * @param integer $vertex (optional) The number of the vertex in the order
     *                          to calculate the distance up to.
     * @return integer|double Returns the distance
     * @since 1.0-sofia
     */
    public function distance($vertex = null)
    {
        if (func_num_args() == 1) {
            return $this->vertices->get($vertex)->potential();
        } else {
            return $this->vertices->last()->potential();
        }
    }

}
