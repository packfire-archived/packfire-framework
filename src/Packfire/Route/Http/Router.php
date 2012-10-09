<?php
namespace Packfire\Route\Http;

use Packfire\Route\Router as CoreRouter;
use Route;
use RedirectRoute;
use Packfire\Net\Http\Url;
use Packfire\Template\Template;

/**
 * Router class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route\Http
 * @since 1.0-elenor
 */
class Router extends CoreRouter {
    
    /**
     * Whether HTTP routing is enabled or not
     * @var boolean
     * @since 1.0-elenor
     */
    private $enabled = true;
    
    /**
     * Perform loading of routes from the routing configuration file
     * @since 1.0-elenor
     */
    public function load(){
        parent::load();
        if($this->service('config.app')){
            $this->enabled = $this->service('config.app')->get('routing', 'enabled');
        }
        $config = new Map(array(
            'rewrite' => '/{class}/{action}',
            'actual' => 'directControllerAccessRoute',
            'method' => null,
            'params' => new Map(array(
                'class' => 'regex/`^([a-zA-Z0-9\_]+)$`',
                'action' => 'regex/`^([a-zA-Z0-9\_]+)$`'
            ))
        ));
        $directControllerAccessRoute = new Route(
                'packfire.directControllerAccess',
                $config);
        $this->add('packfire.directControllerAccess', $directControllerAccessRoute);
    }
    
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