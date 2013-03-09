<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Route\Http;

use Packfire\Route\Router as CoreRouter;
use Packfire\Route\Http\Route;
use Packfire\Route\Http\RedirectRoute;
use Packfire\Net\Http\Url;
use Packfire\Template\Template;

/**
 * Route manager for HTTP
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route\Http
 * @since 1.0-elenor
 */
class Router extends CoreRouter {
        
    /**
     * Factory manufature the route based on the configuration
     * @param string $key Name of the route
     * @param Map $data The configuration of the route
     * @return IRoute Returns the route manufactured
     * @since 1.0-elenor
     */
    protected function routeFactory($key, $data){
        if($data->get('redirect')){
            $route = new RedirectRoute($key, $data);
        }else{
            $route = new Route($key, $data);
        }
        return $route;
    }
    
    /**
     * Prepare a route with the parameters
     * @param Route $route The route to be prepared
     * @param array|Map $params The parameters to prepare
     * @return string The final route URL
     * @since 1.0-elenor
     */
    protected function prepareRoute($route, $params){
        $template = new Template($route->rewrite());
        foreach($params as $name => $value){
            $template->fields()->add($name, Url::encode($value));
        }

        return $template->parse();
    }
    
}