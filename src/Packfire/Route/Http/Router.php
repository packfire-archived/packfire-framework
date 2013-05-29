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
class Router extends CoreRouter
{
    /**
     * Prepare a route with the parameters
     * @param  Route     $route  The route to be prepared
     * @param  array|Map $params The parameters to prepare
     * @return string    The final route URL
     * @since 1.0-elenor
     */
    protected function prepareRoute($route, $params)
    {
        $template = new Template($route->rewrite());
        foreach ($params as $name => $value) {
            $template->fields()->add($name, Url::encode($value));
        }

        return $template->parse();
    }

    public function __invoke($c)
    {
        $this->routeType = '\\Packfire\\Route\\Http\\Route';

        return parent::__invoke($c);
    }

}
