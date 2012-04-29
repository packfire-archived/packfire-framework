<?php
pload('packfire.collection.pList');
pload('pGraphPath');

/**
 * Resolve and find a path between the starting and ending vertices in a graph.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.graph
 * @since 1.0-sofia
 */
class pDijkstra {
    
    /**
     * The graph to work and solve on
     * @var pGraph
     * @since 1.0-sofia
     */
    private $graph;
    
    /**
     * The starting vertex
     * @var IVertex 
     * @since 1.0-sofia
     */
    private $startVertex;
    
    /**
     * The ending vertex
     * @var IVertex
     * @since 1.0-sofia
     */
    private $endVertex;
    
    /**
     * The list of paths found
     * @var pList 
     * @since 1.0-sofia
     */
    private $paths = null;
    
    /**
     * Create a new Dijkstra object
     * @param pGraph $graph The graph to work and solve
     * @since 1.0-sofia
     */
    public function __construct($graph){
        $this->graph = $graph;
        foreach($this->graph->vertices() as $vertex){
            /* @var $vertex IVertex */
            $vertex->reset();
        }
    }
    
    /**
     * Set the starting vertex
     * @param IVertex $start The vertex to start working the shortest path from
     * @return pDijkstra Returns itself for chaining purposes.
     * @since 1.0-sofia
     */
    public function start($start){
        $this->startVertex = $start;
        $this->paths = new pList();
        $this->paths->add(new pList(array($start)));
        return $this;
    }
    
    /**
     * Set the end vertex
     * @param IVertex $end The vertex to end at.
     * @return pDijkstra Returns itself for chaining purposes.
     * @since 1.0-sofia
     */
    public function end($end){
        $this->endVertex = $end;
        return $this;
    }
    
    /**
     * Find the shortest path between the start and end vertex
     * @param IVertex $start (optional) Set the starting vertex to start finding
     *                      the path.
     * @param IVertex $end (optional) Set the ending vertex
     * @return pGraphPath Returns the resolved path if found, or null
     *              if no path is found.
     * @since 1.0-sofia
     */
    public function find($start = null, $end = null){
        if(func_num_args() > 0){
            $this->start($start);
        }
        if(func_num_args() == 2){
            $this->end($end);
        }
        
        $this->calculatePotentials($this->startVertex, $this->endVertex);
        
        $aPath = array();
        $vertex = $this->endVertex;
        
        // loop through where the pathfinding came from
        while ($vertex->id() != $this->startVertex->id()) {
            $aPath[] = $vertex;
            $vertex = $vertex->from();
            if($vertex == null){
                return null;
            }
        }
        
        $aPath[] = $this->startVertex;
        $path = new pGraphPath(new pList(array_reverse($aPath)));
        return $path;
    }
    
    /**
     * Calculate the potentials to every other vertex from the start vertex
     * @param IVertex $vertex The vertex to calculate potentials
     * @param IVertex $end The destination vertex
     * @since 1.0-sofia
     */
    private function calculatePotentials($vertex, $end){
        $connections = $vertex->connections();
        $sorted = array_flip($connections->toArray());
        krsort($sorted);
        foreach($connections as $id => $cost){
            /* @var $connection IVertex */
            $connection = $this->graph->get($id);
            $connection->setPotential($vertex->potential() + $cost, $vertex);
            
            foreach($this->paths as $path){
                /* @var $path pList */
                $last = $path->last();
                if($last && $last->id() == $vertex->id()){
                    $this->paths->add(new pList(
                            array_merge($path->toArray(), array($connection))
                        ));
                }
            }
        }
        
        // mark the vector as you have already passed by
        $vertex->mark();
        
        foreach($sorted as $id){
            $nextVertex = $this->graph->get($id);
            if(!$nextVertex->passed()){
                $this->calculatePotentials($nextVertex, $end);
            }
        }
    }
    
}