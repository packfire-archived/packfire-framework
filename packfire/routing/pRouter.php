<?php
pload('packfire.collection.pMap');
pload('packfire.pTemplate');
pload('packfire.net.http.pUrl');

/**
* Handles URL rewritting and controller routing
*
* @author Sam-Mauris Yong / mauris@hotmail.sg
* @license http://www.opensource.org/licenses/bsd-license New BSD License
* @package packfire.routing
* @since 1.0-sofia
*/
class pRouter {
    
    /**
     * The routing key to use
     * (as defined in .htaccess) 
     */
    const KEY = '_urlroute_';
    
    /**
     * The collection of routing entries
     * @var pMap
     * @since 1.0-sofia
     */
    private $routes;
    
    /**
     * Create a new pRouter
     * @since 1.0-sofia 
     */
    public function __construct(){
        $this->routes = new pMap();
    }
    
    /**
     * Add a new routing entry to the router
     * @param string $key The routing key that uniquely identify this routing entry.
     * @param pRoute $route  The route entry
     * @since 1.0-sofia
     */
    public function add($key, $route){
        $this->routes[$key] = $route;
    }
    
    /**
     * Get the URL for a particular routing key
     * @param string $key The routing key that uniquely identify the routing
     *                    entry to fetch.
     * @param array|pMap $params (optional) The parameters to insert into the URL.
     * @return string Returns the URL
     * @since 1.0-sofia
     */
    public function route($key, $params = array()){
        $route = $this->routes->get($key);
        if($route === null){
            // TODO throw exception
        }
        $t = new pTemplate($route->rewrite());
        foreach($params as $name => $value){
            $t->fields()->add($name, pUrl::encode($value));
        }

        return $t->parse();
    }
    
}