<?php
pload('IRoute');

/**
 * pRedirectRoute Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing
 * @since 1.0-elenor
 */
class pRedirectRoute implements IRoute {
    
    private $name;
    
    /**
     * The rewritten version of the URL
     * @var string 
     * @since 1.0-elenor
     */
    private $rewrite;
    
    /**
     * The redirect URL
     * @var string|pUrl
     * @since 1.0-elenor
     */
    private $redirect;
    
    /**
     * The HTTP redirect response code
     * @var integer
     * @since 1.0-elenor
     */
    private $code;
    
    /**
     * Create a new pRedirectRoute object
     * @param string $name The name of the route
     * @param array|Map $data The configuration data entry
     * @since 1.0-elenor
     */
    public function __construct($name, $data) {
        $this->name = $name;
        $this->rewrite = $data->get('rewrite');
        $this->redirect = $data->get('redirect');
        $this->code = (int)$data->get('code', 302);
    }

    public function match($request) {
        $url = $request->pathInfo();
        $method = strtolower($request->method());
        if(!$this->httpMethod() || 
                (is_string($this->httpMethod)
                && $this->httpMethod == strtolower($method))
                || (is_array($this->httpMethod)
                && in_array(strtolower($method), $this->httpMethod))){
        
            $template = new pTemplate($this->rewrite);
            $tokens = $template->tokens();
            foreach ($tokens as $token) {
                $value = $this->params->get($token);
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
                return true;
            }
        }
        return false;
    }
    
    /**
     * Get the name of the route
     * @return string Returns name of the route
     * @since 1.0-elenor
     */
    public function name() {
        return $this->name;
    }
    
    /**
     * Get the redirection URL of the route
     * @return string|pUrl Returns the redirection URL
     * @since 1.0-elenor
     */
    public function redirect(){
        return $this->redirect;
    }
    
    /**
     * Get the HTTP response code of the redirect
     * @return string Returns the response code
     * @since 1.0-elenor
     */
    public function code(){
        return $this->code;
    }

}