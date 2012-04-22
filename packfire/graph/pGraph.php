<?php
pload('pDijkstra');

/**
 * A graph representation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.graph
 * @since 1.0-sofia
 */
class pGraph {
    
    /**
     * The vertices in the graph
     * @var pMap 
     * @since 1.0-sofia
     */
    private $vertices;
    
    /**
     * Create a new Graph object 
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->vertices = new pMap();
    }
    
    /**
     * Add a vertex to the graph
     * @param IVertex $vertex The vertex to be added
     * @since 1.0-sofia
     */
    public function add($vertex){
        $this->vertices[$vertex->id()] = $vertex;
    }
    
    /**
     * Get a vertex by its ID
     * @param string|integer $vertexId The identifier of the vertex
     * @return IVertex The vertex found is returned.  
     * @since 1.0-sofia
     */
    public function get($vertexId){
        return $this->vertices->get($vertexId);
    }
    
    /**
     * Get all the vertices in the graph
     * @return pMap Returns all the vertices in the graph
     * @since 1.0-sofia
     */
    public function vertices(){
        return $this->vertices;
    }
    
    /**
     * Find the shortest path between two vertices in the graph
     * @param string|integer $start The identifier of the start vertex
     * @param string|integer $end The identifier of the end vertex
     * @return pGraphPath Returns the path if found or null if no path is found.
     * @since 1.0-sofia
     */
    public function find($start, $end){
        $dijkstra = new pDijkstra($this);
        $path = $dijkstra->start($this->get($start))
                ->end($this->get($end))->find();
        return $path;
    }
    
}