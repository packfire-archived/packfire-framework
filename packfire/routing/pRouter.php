<?php
pload('packfire.collection.pMap');
pload('packfire.template.pTemplate');
pload('packfire.net.http.pUrl');
pload('packfire.exception.pNullException');
pload('packfire.ioc.pBucketUser');

/**
 * Handles URL rewritting and controller routing
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing
 * @since 1.0-sofia
 */
class pRouter extends pBucketUser {
    
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
     * Perform loading of routes from the routing configuration file
     * @since 1.0-sofia
     */
    public function load(){
        $settings = $this->service('config.routing');
        $routes = $settings->get();
        foreach($routes as $key => $data){
            $data = new pMap($data);
            $route = new pRoute($data->get('rewrite'), $data->get('actual'),
                    $data->get('method'), $data->get('params'));
            $this->add($key, $route);
        }
        $directControllerAccessRoute = new pRoute('/{class}/{action}',
                'directControllerAccessRoute',
                null,
                new pMap(array(
                    'class' => '([a-zA-Z0-9\_]+)',
                    'action' => '([a-zA-Z0-9\_]+)'
                )));
        $this->add('packfire.DCARoute', $directControllerAccessRoute);
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
     * @param pHttpRequest $request The HTTP request to perform routing
     * @return pRoute Returns the route found based on the request or NULL
     *              if no suitable route is found.
     * @since 1.0-sofia
     */
    public function route($request){        
        $url = '/' . (string)$request->get()->get(self::KEY);
        $method = strtolower($request->method());
        
        foreach ($this->routes as $route) {
            
            // check whether HTTP method matches for RESTful routing
            if(!$route->httpMethod() || 
                    (is_string($route->httpMethod())
                    && $route->httpMethod() == strtolower($method))
                    || (is_array($route->httpMethod())
                    && in_array(strtolower($method), $route->httpMethod()))){
                
                $template = new pTemplate($route->rewrite());
                $tokens = $template->tokens();
                foreach ($tokens as $token) {
                    $value = $route->params()->get($token);
                    if (!$value) {
                        $value = '(*)';
                    }
                    $template->fields()->add($token,
                            '(?P<' . $token . '>' . $value . ')');
                }
                $matches = array();
                
                // perform the URL matching
                $matchResult = preg_match('`^' . $template->parse() .
                        '([/]{0,1})$`is', $url, $matches);
                
                if ($matchResult) {
                    $params = array();
                    foreach ($tokens as $key) {
                        $params[$key] = $matches[$key];
                    }
                    if($method == 'get'){
                        foreach($_GET as $key => $value){
                            if(!array_key_exists($key, $params)){ 
                                // checking to prevent normal injection
                                $params[$key] = $value; 
                            }
                        }
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