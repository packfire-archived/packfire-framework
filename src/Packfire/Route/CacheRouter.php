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

use Packfire\DateTime\TimeSpan;

/**
 * Handles URL rewritting and controller routing
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route
 * @since 2.1.0
 */
class CacheRouter implements IRouter
{
    /**
     * The original router
     * @var \\Packfire\\Route\\IRouter
     * @since 2.1.0
     */
    private $router;

    /**
     * The caching mechanism
     * @var \\Packfire\\Cache\\ICache
     * @since 2.1.0
     */
    private $cache;

    public function __construct($router, $cache)
    {
        $this->router = $router;
        $this->cache = $cache;
    }

    public function route($request)
    {
        $cacheId = 'route.' . $request->method() . sha1($request->uri() . $request->queryString());
        if ($this->cache->check($cacheId)) {
            $route = $this->cache->get($cacheId);
        } else {
            $route = $this->router->route($request);
            if ($route) {
                $this->cache->set($cacheId, $route, new TimeSpan(7200));
            }
        }

        return $route;
    }

    public function to($key, $params = array())
    {
        return $this->router->to($key, $params);
    }
}
