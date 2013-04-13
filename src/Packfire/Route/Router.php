<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Route;

use Packfire\Collection\Map;
use Packfire\Exception\NullException;
use Packfire\Exception\InvalidRequestException;
use Packfire\FuelBlade\IConsumer;

/**
 * Handles URL rewritting and controller routing
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route
 * @since 1.0-sofia
 */
abstract class Router implements IConsumer {
    
    protected $routeType;
    
    /**
     * The collection of routing entries
     * @var Map
     * @since 1.0-sofia
     */
    private $routes;
    
    /**
     * Create a new Router object
     * @since 1.0-sofia 
     */
    public function __construct(){
        $this->routes = new Map();
    }
    
    /**
     * Add a new routing entry to the router
     * @param string $key The routing key that uniquely identify this
     *               routing entry.
     * @param Route $route  The route entry
     * @since 1.0-sofia
     */
    public function add($key, $route){
        if($this->routes){
            $this->routes[$key] = $route;
        }else{
            throw new InvalidRequestException('Router has not been loaded before the add request was called.');
        }
    }
    
    /**
     * Get the map of entries.
     * @return Map Returns a Map of routing entries.
     * @since 1.0-sofia
     */
    public function entries(){
        return $this->routes;
    }
    
    /**
     * Perform routing operation and return the route entry
     * @param IAppRequest $request The HTTP request to perform routing
     * @return IRoute Returns the route found based on the request or NULL
     *              if no suitable route is found.
     * @since 1.0-elenor
     */
    public function route($request){
        if($this->routes){
            foreach ($this->routes as $route) {
                if($route->match($request)){
                    return $route;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Get the URL for a particular routing key
     * @param string $key The routing key that uniquely identify the routing
     *                    entry to fetch.
     * @param array|Map $params (optional) The parameters to insert into
     *                   the URL.
     * @return string Returns the URL
     * @since 1.0-sofia
     */
    public function to($key, $params = array()){
        if($this->routes){
            $route = $this->routes->get($key);
            if($route === null){
                throw new NullException(
                        sprintf('Routing route "%s" was not found in the'
                                . ' router\'s entries.', $key));
            }

            return $this->prepareRoute($route, $params);
        }else{
            return null;
        }
    }
    
    /**
     * Prepare a route with the parameters
     * @param Route $route The route to be prepared
     * @param array|Map $params The parameters to prepare
     * @return string The final route URL
     * @since 1.0-elenor
     */
    protected abstract function prepareRoute($route, $params);
    
    public function __invoke($c){
        if(isset($c['config.routing'])){
            $settings = $c['config.routing'];
            $routes = $settings->get();
            $type = $this->routeType;
            foreach($routes as $key => $data){
                $data = new Map($data);
                $route = new $type($key, $data);
                $this->add($key, $route);
            }
        }
        return $this;
    }
    
}