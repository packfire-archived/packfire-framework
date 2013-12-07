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
use Packfire\FuelBlade\ConsumerInterface;

/**
 * Handles URL rewritting and controller routing
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route
 * @since 1.0-sofia
 */
abstract class Router implements ConsumerInterface, IRouter
{
    protected $routeType;

    /**
     * The collection of routing entries
     * @var \Packfire\Collection\Map
     * @since 1.0-sofia
     */
    private $routes;

    /**
     * Create a new Router object
     * @since 1.0-sofia
     */
    public function __construct()
    {
        $this->routes = new Map();
    }

    /**
     * Add a new routing entry to the router
     * @param string $key The routing key that uniquely identify this
     *               routing entry.
     * @param \Packfire\Route\IRoute $route The route entry
     * @since 1.0-sofia
     */
    public function add($key, $route)
    {
        if ($this->routes) {
            $this->routes[$key] = $route;
        } else {
            throw new InvalidRequestException('Router has not been loaded before the add request was called.');
        }
    }

    /**
     * Get the map of entries.
     * @return Map Returns a Map of routing entries.
     * @since 1.0-sofia
     */
    public function entries()
    {
        return $this->routes;
    }

    /**
     * Perform routing operation and return the route entry
     * @param  \Packfire\Application\IAppRequest $request The HTTP request to perform routing
     * @return \Packfire\Route\IRoute            Returns the route found based on the request or NULL
     *              if no suitable route is found.
     * @since 1.0-elenor
     */
    public function route($request)
    {
        foreach ($this->routes as $route) {
            if ($route->match($request)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Get the URL for a particular routing key
     * @param string $key The routing key that uniquely identify the routing
     *                    entry to fetch.
     * @param array|\Packfire\Collection\Map $params (optional) The parameters to insert into
     *                   the URL.
     * @return string Returns the URL
     * @since 1.0-sofia
     */
    public function to($key, $params = array())
    {
        $route = $this->routes->get($key);
        if ($route === null) {
            throw new NullException(
                sprintf(
                    'Routing route "%s" was not found in the'
                    . ' router\'s entries.',
                    $key
                )
            );
        }

        return $this->prepareRoute($route, $params);
    }

    /**
     * Prepare a route with the parameters
     * @param  Route     $route  The route to be prepared
     * @param  array|Map $params The parameters to prepare
     * @return string    The final route URL
     * @since 1.0-elenor
     */
    abstract protected function prepareRoute($route, $params);

    public function __invoke($c)
    {
        if (isset($c['config.routes'])) {
            $settings = $c['config.routes'];
            $routes = $settings->get();
            if (is_array($routes)) {
                $type = $this->routeType;
                foreach ($routes as $key => $data) {
                    $data = new Map($data);
                    $route = new $type($key, $data);
                    $this->add($key, $route);
                }
            }
        }

        return $this;
    }
}
