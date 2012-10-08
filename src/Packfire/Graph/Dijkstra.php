<?php
namespace Packfire\Graph;

use Packfire\Collection\ArrayList;
use Path;
use VertexSorter;

/**
 * Dijkstra class
 * 
 * Resolve and find a path between the starting and ending vertices in a graph
 * using Dijkstra's Algorithm.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Graph
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
     * Create a new pDijkstra object
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
     * @return Dijkstra Returns itself for chaining purposes.
     * @since 1.0-sofia
     */
    public function start($start){
        $this->startVertex = $start;
        return $this;
    }
    
    /**
     * Set the end vertex
     * @param IVertex $end The vertex to end at.
     * @return Dijkstra Returns itself for chaining purposes.
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
     * @return Path Returns the resolved path if found, or null
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
        
        $this->startVertex->setPotential(0, $this->startVertex);
        $this->calculate();
        
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
        
        $path = new Path(new ArrayList(array_reverse($aPath)));
        return $path;
    }
    
    /**
     * Get the vertex with the least potential that has been yet to pass
     * @param array $map (reference) The map to get the vertex from
     * @return IVertex Returns the vertex with the least potential if found, or null otherwise
     * @since 1.0-sofia
     */
    private function getLeastPotential(&$map){
        $leastPotential = null; 
        
        /* @var $vertex IVertex */
        $vertex = reset($map);
        if($vertex && $vertex->potential() !== null
                && ($leastPotential == null
                || $vertex->potential() < $leastPotential->potential())){
            $leastPotential = $vertex;
        }
        return $leastPotential;
    }
    
    /**
     * Perform the reordering of a vertex
     * @param array $map (reference) The array reference to perform the re-ordering on.
     * @param IVertex $vertex The vertex to re-order based on the potential
     * @since 1.0-sofia
     */
    private function reorder(&$map, $vertex){
        if(array_key_exists($vertex->id(), $map)){
            unset($map[$vertex->id()]);
            reset($map);
            $point = 0;
            while(($next = current($map)) !== false
                    && $next->potential() !== null 
                    && $next->potential() < $vertex->potential()){
                ++$point;
                next($map);
            }
            $map = array_slice($map, 0, $point, true) 
                    + array($vertex->id() => $vertex) 
                    + array_slice($map, $point, null, true);
        }
    }
    
    /**
     * Perform the calculation for the vertices
     * @since 1.0-sofia 
     */
    private function calculate(){
        $map = $this->graph->vertices()->toArray();
        
        $sorter = new VertexSorter();
        $sorter->sort($map);
        
        $remaining = count($map);
        while($remaining > 0){
            $vertex = $this->getLeastPotential($map);
            if($vertex == null){
                break;
            }
            unset($map[$vertex->id()]);
            --$remaining;
            $neighbours = $vertex->connections();

            foreach($neighbours as $id => $cost){
                /* @var $neighbour IVertex */
                $neighbour = $this->graph->get($id);
                $neighbour->setPotential($vertex->potential() + $cost, $vertex);
                $this->reorder($map, $neighbour);
            }
        }
    }
}