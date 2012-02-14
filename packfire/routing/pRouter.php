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
     * @param type $key
     * @param type $route 
     * @since 1.0-sofia
     */
    public function add($key, $route){
        $this->routes[$key] = $route;
    }
    
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