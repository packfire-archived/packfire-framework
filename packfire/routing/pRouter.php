<?php
pload('packfire.collection.pMap');
pload('packfire.net.http.pUrl');
pload('packfire.exception.pNullException');
pload('packfire.ioc.pBucketUser');
pload('pRoute');
pload('pRedirectRoute');

/**
 * Handles URL rewritting and controller routing
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing
 * @since 1.0-sofia
 */
class pRouter extends pBucketUser implements IService {
    
    /**
     * The collection of routing entries
     * @var pMap
     * @since 1.0-sofia
     */
    private $routes;
    
    /**
     * Flags whether rewritting is enabled or disabled.
     * @var boolean 
     * @since 1.0-sofia
     */
    private $rewrite;
    
    /**
     * Create a new pRouter object
     * @param boolean $rewrite (optional) Flag whether rewritting is enabled or
     *                         disabled. Defaults to true.
     * @since 1.0-sofia 
     */
    public function __construct($rewrite = true){
        $this->rewrite = $rewrite;
        $this->routes = new pMap();
    }
    
    /**
     * Perform loading of routes from the routing configuration file
     * @since 1.0-sofia
     */
    public function load(){
        $settings = $this->service('config.routing');
        $routes = $settings->get();
        foreach($routes as $key => $data){
            $data = new pMap($data);
            $route = self::routeFactory($key, $data);
            $this->add($key, $route);
        }
        $config = new pMap(array(
            'rewrite' => '/{class}/{action}',
            'actual' => 'directControllerAccessRoute',
            'method' => null,
            'params' => new pMap(array(
                'class' => '([a-zA-Z0-9\_]+)',
                'action' => '([a-zA-Z0-9\_]+)'
            ))
        ));
        $directControllerAccessRoute = new pRoute(
                'packfire.directControllerAccess',
                $config);
        $this->add('packfire.DCARoute', $directControllerAccessRoute);
    }
    
    /**
     * Factory manufature the route based on the configuration
     * @param string $key Name of the route
     * @param pMap $data The configuration of the route
     * @return IRoute Returns the route manufactured
     * @since 1.0-elenor
     */
    private static function routeFactory($key, $data){
        if($data->get('redirect')){
            $route = new pRedirectRoute($key, $data);
        }else{
            $route = new pRoute($key, $data);
        }
        return $route;
    }
    
    /**
     * Add a new routing entry to the router
     * @param string $key The routing key that uniquely identify this
     *               routing entry.
     * @param pRoute $route  The route entry
     * @since 1.0-sofia
     */
    public function add($key, $route){
        $this->routes[$key] = $route;
    }
    
    /**
     * Get the map of entries.
     * @return pMap Returns a pMap of routing entries.
     * @since 1.0-sofia
     */
    public function entries(){
        return $this->routes;
    }
    
    /**
     * Perform routing operation and return the route entry
     * @param pHttpPhpRequest $request The HTTP request to perform routing
     * @return pRoute Returns the route found based on the request or NULL
     *              if no suitable route is found.
     * @since 1.0-sofia
     */
    public function route($request){
        $url = $request->pathInfo();
        $method = strtolower($request->method());
        
        foreach ($this->routes as $route) {
            if($route->match($method, $url)){
                return $route;
            }
        }
        
        return null;
    }
    
    /**
     * Get the URL for a particular routing key
     * @param string $key The routing key that uniquely identify the routing
     *                    entry to fetch.
     * @param array|pMap $params (optional) The parameters to insert into
     *                   the URL.
     * @return string Returns the URL
     * @since 1.0-sofia
     */
    public function to($key, $params = array()){
        $route = $this->routes->get($key);
        if($route === null){
            throw new pNullException(
                    sprintf('Routing route "%s" was not found in the'
                            . ' router\'s entries.', $key));
        }
        $template = new pTemplate($route->rewrite());
        foreach($params as $name => $value){
            $template->fields()->add($name, pUrl::encode($value));
        }

        return $template->parse();
    }
    
}