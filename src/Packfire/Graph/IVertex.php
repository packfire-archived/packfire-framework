<?php

/**
 * IVertex interface
 * 
 * Vertex abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Graph
 * @since 1.0-sofia
 */
interface IVertex {
    
    /**
     * Get the vertex that best came here so far
     * @return IVertex Returns the vertex that best came here
     * @since 1.0-sofia
     */
    public function from();
    
    /**
     * Get the identifier of this vertex
     * @return string|integer Returns the identifier of this vertex
     * @since 1.0-sofia
     */
    public function id();
    
    /**
     * Get the potential so far
     * @return integer|double Returns the potential so far
     * @since 1.0-sofia
     */
    public function potential();
    
    /**
     * Set the potential for this vertex
     * @param integer|double $potential The potential in total
     * @param IVertex $from The vertex that best came from
     * @return boolean Returns true if set is successful, false otherwise.
     * @since 1.0-sofia
     */
    public function setPotential($potential, $from);
    
    /**
     * Define that this vertex can connect to another vertex
     * @param IVertex $vertex The vertex to connect to
     * @param integer|double $cost The cost of moving from this vertex to the
     *                  connecting vertex.
     * @since 1.0-sofia
     */
    public function connect($vertex, $cost);
    
    /**
     * Get the connections from this vertex and their associated costs.
     * @return Map Returns an array containing the containing vertex ID
     *               and their associated costs.
     * @since 1.0-sofia
     */
    public function connections();
    
    /**
     * Reset the vertex for another run. 
     * @since 1.0-sofia
     */
    public function reset();
    
}