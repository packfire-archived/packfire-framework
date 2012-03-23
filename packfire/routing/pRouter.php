<?php
pload('packfire.collection.pMap');
pload('packfire.template.pTemplate');
pload('packfire.net.http.pUrl');
pload('packfire.exception.pNullException');

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
     * @since 1.0-sofia
     */
    const KEY = '_urlroute_';
    
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
     * Create a new pRouter
     * @param boolean $rewrite (optional) Flag whether rewritting is enabled or
     *                         disabled. Defaults to true.
     * @since 1.0-sofia 
     */
    public function __construct($rewrite = true){
        $this->rewrite = $rewrite;
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
     * Get the map of entries.
     * @return pMap Returns a pMap of routing entries.
     * @since 1.0-sofia
     */
    public function entries(){
        return $this->routes;
    }
    
    /**
     * Perform routing operation and return the route entry
     * @param pHttpClientRequest $clientRequest The client request
     * @return pRoute Returns the route found based on the request
     * @since 1.0-sofia
     */
    public function route($clientRequest){        
        $url = '/' . (string)$clientRequest->get()->get(self::KEY);
        $method = strtolower($clientRequest->method());
        
        if ($url == null) {
            $url == '';
        }
        
        foreach ($this->routes as $route) {
            // check whether HTTP method matches for RESTful routing
            
            if(!$route->httpMethod() || strtolower($route->httpMethod()) == $method){
                $t = new pTemplate($route->rewrite());
                $tokens = $t->tokens();
                foreach ($tokens as $a) {
                    $v = $route->params()->get($a);
                    if (!$v) {
                        $v = '(*)';
                    }
                    $t->fields()->add($a, '(?P<' . $a . '>' . $v . ')');
                }
                $matches = array();
                $i = preg_match('`^' . $t->parse() . '([/]{0,1})$`is', $url, $matches);
                if ($i) {
                    $params = array();
                    foreach ($tokens as $a) {
                        $params[$a] = $matches[$a];
                    }
                    $route->params()->append($params);
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
     * @param array|pMap $params (optional) The parameters to insert into the URL.
     * @return string Returns the URL
     * @since 1.0-sofia
     */
    public function to($key, $params = array()){
        $route = $this->routes->get($key);
        if($route === null){
            throw new pNullException(
                    sprintf('Routing route "%s" is not found in the router\'s entries.', $key));
        }
        $t = new pTemplate($route->rewrite());
        foreach($params as $name => $value){
            $t->fields()->add($name, pUrl::encode($value));
        }

        return $t->parse();
    }
    
}