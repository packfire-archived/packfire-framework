<?php
pload('packfire.routing.IRoute');
pload('packfire.net.http.pHttpMethod');
pload('packfire.template.pTemplate');
pload('packfire.collection.pMap');
pload('packfire.validator.pSerialValidator');
pload('packfire.validator.pNumericValidator');
pload('packfire.validator.pMatchValidator');
pload('packfire.validator.pRegexValidator');
pload('packfire.validator.pCallbackValidator');

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
class pHttpRoute implements IRoute {
    
    /**
     * The name of the route
     * @var string
     * @since 1.0-elenor
     */
    private $name;
    
    /**
     * The HTTP method that this URL route will cater for. Defaults to GET.
     * @var string|pList|array
     * @since 1.0-elenor
     */
    private $httpMethod = pHttpMethod::GET;

    /**
     * The rewritten relative-to-host URL
     * @var string
     * @since 1.0-sofia
     */
    private $rewrite;

    /**
     * The name of the controller class to route to
     * @var string
     * @since 1.0-elenor
     */
    private $actual;

    /**
     * The parameters in this routing
     * @var pMap
     * @since 1.0-elenor
     */
    private $params;

    /**
     * Create a new pRoute object
     * @param string $name The name of the route
     * @param array|pMap $data The configuration data entry
     * @since 1.0-elenor
     */
    function __construct($name, $data){
        if(!($data instanceof pMap)){
            $data = new pMap($data);
        }
        $this->name = $name;
        $this->rewrite = $data->get('rewrite');
        $this->actual = $data->get('actual');
        $this->httpMethod = $data->get('method');
        $this->params = new pMap($data->get('params'));
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
     * @return pMap Returns a hash map
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
        $oriMethod = $request->method();
        if($request->headers()->keyExists('X-HTTP-Method')){
            $oriMethod = $request->headers()->get('X-HTTP-Method');
        }
        if($request->headers()->keyExists('X-HTTP-Method-Override')){
            $oriMethod = $request->headers()->get('X-HTTP-Method-Override');
        }
        $method = strtolower($oriMethod);
        
        $validation = false;
        // check whether HTTP method matches for RESTful routing
        if(!$this->httpMethod() || 
                (is_string($this->httpMethod)
                && $this->httpMethod == strtolower($method))
                || (is_array($this->httpMethod)
                && in_array(strtolower($method), $this->httpMethod))){
            
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
                    if($this->params->keyExists($key)){
                        $data[$key] = $value;
                    }
                }
                $data += $request->get()->toArray();
                if($method == 'post'){
                    $data += $request->post()->toArray();
                }

                $validation = true;
                $params = array();
                foreach($this->params as $key => $value){
                    if(array_key_exists($key, $data)){
                        $param = $data[$key];
                        $validation = $this->validateParam($value, $param);
                        if(!$validation){
                            break;
                        }
                        $params[$key] = $param;
                    }else{
                        $validation = false;
                        break;
                    }
                }
                if($validation){
                    $this->params = new pMap($params);
                }
            }else{
                $validation = false;
            }
        }
        return $validation;
    }
    
    protected function validateParam($rule, &$value){
        $original = $value;
        $slashPos = strpos($rule, '/');
        $options = '';
        if($slashPos !== false){
            $options = substr($rule, $slashPos + 1);
            $rule = substr($rule, 0, $slashPos);
        }
        $validator = new pSerialValidator();
        switch($rule){
            case 'any':
                break;
            case 'numeric':
            case 'number':
            case 'num':
                $validator->add(new pNumericValidator());
                $value += 0;
                break;
            case 'float':
            case 'real':
            case 'double':
                $validator->add(new pNumericValidator());
                $validator->add(new pCallbackValidator(function($value){
                    return is_float($value + 0);
                }));
                $value += 0;
                break;
            case 'integer':
            case 'int':
            case 'long':
                $validator->add(new pNumericValidator());
                $validator->add(new pCallbackValidator(function($value){
                    return is_int($value + 0);
                }));
                $value += 0;
                break;
            case 'bool':
            case 'boolean':
                $validator->add(
                    new pMatchValidator(array('true', 'false', '0', '1', 'on', 'off'))
                );
                $value = in_array($value, array('true', '1', 'on'), true);
                break;
            case 'alnum':
                $options = '/^[a-zA-Z0-9]+$/';
                $validator->add(new pRegexValidator($options));
                break;
            case 'email':
                $validator->add(new pEmailValidator());
                break;
            case 'alpha':
                $options = '/^[a-zA-Z]+$/';
                $validator->add(new pRegexValidator($options));
                break;
            case 'regex':
                $validator->add(new pRegexValidator($options));
                break;
            default:
                $validator->add(new pMatchValidator($rule));
                break;
        }
        return $validator->validate($original);
    }
    
}