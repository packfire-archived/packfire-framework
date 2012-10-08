<?php
pload('packfire.routing.pRoute');
pload('packfire.net.http.pHttpMethod');
pload('packfire.template.pTemplate');
pload('packfire.collection.pMap');

/**
 * pHttpRoute class
 * 
 * A HTTP route entry
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing.http
 * @since 1.0-elenor
 */
class pHttpRoute extends pRoute {
    
    /**
     * The HTTP method that this URL route will cater for. Defaults to GET.
     * @var string|pList|array
     * @since 1.0-elenor
     */
    protected $httpMethod = pHttpMethod::GET;

    /**
     * The rewritten relative-to-host URL
     * @var string
     * @since 1.0-sofia
     */
    protected $rewrite;

    /**
     * Create a new pHttpRoute object
     * @param string $name The name of the route
     * @param array|Map $data The configuration data entry
     * @since 1.0-elenor
     */
    function __construct($name, $data){
        if(!($data instanceof Map)){
            $data = new Map($data);
        }
        $this->name = $name;
        $this->rewrite = $data->get('rewrite');
        $this->actual = $data->get('actual');
        $this->httpMethod = $data->get('method');
        $this->params = new Map($data->get('params'));
    }

    /**
     * Get the name of the route
     * @return string Returns name of the route
     * @since 1.0-elenor
     */
    public function name(){
        return $this->name;
    }
    
    /**
     * Get the HTTP method for this URL route
     * @return string Returns the HTTP method
     * @since 1.0-elenor
     */
    public function httpMethod(){
        return $this->httpMethod;
    }

    /**
     * Get the rewritten relative-to-host URL
     * @return string Returns the relative URL
     * @since 1.0-elenor
     */
    public function rewrite(){
        return $this->rewrite;
    }

    /**
     * Get the name of the controller class to route to
     * @return string Returns the controller class name
     * @since 1.0-sofia
     */
    public function actual(){
        return $this->actual;
    }

    /**
     * Get the hash map of parameters for the route
     * @return Map Returns a hash map
     * @since 1.0-sofia
     */
    public function params(){
        return $this->params;
    }
    
    /**
     * Check whether the route matches the request
     * @param pHttpAppRequest $request The locator requested by the client
     * @return boolean Returns true if the route matches, false otherwise
     * @since 1.0-elenor 
     */
    public function match($request){
        $url = $request->pathInfo();
        $method = strtolower($request->method());
        
        $validation = false;
        // check whether HTTP method matches for RESTful routing
        if(!$this->httpMethod() || 
                (is_string($this->httpMethod)
                && $this->httpMethod == strtolower($method))
                || (is_array($this->httpMethod)
                && in_array(strtolower($method), $this->httpMethod))){
            if($this->params){
                $template = new pTemplate($this->rewrite);
                $tokens = $template->tokens();
                foreach ($tokens as $token) {
                    $template->fields()->add($token,
                            '(?P<' . $token . '>(.+))');
                }
                $urlData = array();


                // perform the URL matching
                $urlMatch = preg_match('`^' . $template->parse() .
                        '([/]{0,1})$`is', $url, $urlData);

                if($urlMatch){
                    $data = array();
                    foreach($urlData as $key => $value){
                        $data[$key] = $value;
                    }
                    $data += $request->get()->toArray();
                    if($method == 'post'){
                        $data += $request->post()->toArray();
                    }

                    $params = array();
                    $validation = $this->validateArray($this->params, $data, $params);
                    if($validation){
                        $this->params = new Map($params);
                    }

                }else{
                    $validation = false;
                }
            }
        }
        return $validation;
    }
    
}