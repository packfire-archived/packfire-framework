<?php
namespace Packfire\Graph;

use Packfire\Graph\IVertex;
use Packfire\Collection\Map;
use Packfire\Exception\InvalidArgumentException;

/**
 * Vertex class
 * 
 * A vertex on the graph
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Graph
 * @since 1.0-sofia
 */
class Vertex implements IVertex {
    
    /**
     * The unique identity of the vertex
     * @var integer|string
     * @since 1.0-sofia
     */
    private $id;
    
    /**
     * The connections that this vertex can connect to
     * @var Map 
     * @since 1.0-sofia
     */
    private $connections;
    
    /**
     * The potential of the best path to this vertex so far
     * @var integer|double 
     * @since 1.0-sofia
     */
    private $potential;
    
    /**
     * The best path so far, the best.
     * @var IVertex 
     * @since 1.0-sofia
     */
    private $bestFrom;
    
    /**
     * Create a new Vertex object
     * @param string|integer $id The unique identifier of the vertex in the graph
     * @since 1.0-sofia
     */
    public function __construct($id){
        $this->id = $id;
        $this->connections = new Map();
    }
    
    /**
     * Define that this vertex can connect to another vertex
     * @param IVertex $vertex The vertex to connect to
     * @param integer|double $cost The cost of moving from this vertex to the
     *                  connecting vertex.
     * @throws InvalidArgumentException Thrown when $vertex is an instance of itself.
     * @since 1.0-sofia
     */
    public function connect($vertex, $cost){
        if($this == $vertex || $this->id() == $vertex->id()){
            throw new InvalidArgumentException(
                    'Vertex::connect',
                    'vertex',
                    'not an instance of itself',
                    'Vertex#' . $vertex->id()
                );
        }
        $this->connections[$vertex->id()] = $cost;
    }
    
    /**
     * Get the identifier of this vertex
     * @return string|integer Returns the identifier of this vertex
     * @since 1.0-sofia
     */
    public function id(){
        return $this->id;
    }
    
    /**
     * Get the connections from this vertex and their associated costs.
     * @return Map Returns an array containing the containing vertex ID
     *               and their associated costs.
     * @since 1.0-sofia
     */
    public function connections(){
        return $this->connections;
    }
    
    /**
     * Get the potential so far
     * @return integer|double Returns the potential so far
     * @since 1.0-sofia
     */
    public function potential(){
        return $this->potential;
    }
    
    /**
     * Get the vertex that best came here so far
     * @return IVertex Returns the vertex that best came here
     * @since 1.0-sofia
     */
    public function from(){
        return $this->bestFrom;
    }
    
    /**
     * Set the potential for this vertex
     * @param integer|double $potential The potential in total
     * @param IVertex $from The vertex that best came from
     * @return boolean Returns true if set is successful, false otherwise.
     * @since 1.0-sofia
     */
    public function setPotential($potential, $from){
        if((!$this->potential || (int)$potential < $this->potential)){
            $this->potential = $potential;
            $this->bestFrom = $from;
            return true;
        }
        return false;
    }
    
    /**
     * Reset the vertex for another run. 
     * @since 1.0-sofia
     */
    public function reset(){
       $this->passed = false;
       $this->bestFrom = null;
       $this->potential = null;
    }
    
}