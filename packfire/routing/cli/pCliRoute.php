<?php
pload('packfire.routing.IRoute');

/**
 * pCliRoute class
 * 
 * A command-line interface route entry
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing.cli
 * @since 1.0-elenor
 */
class pCliRoute implements IRoute {
    
    /**
     * The name of the route
     * @var string
     * @since 1.0-elenor
     */
    private $name;
    
    /**
     * The route parameters to check
     * @var pMap
     * @since 1.0-elenor
     */
    private $params;
    
    /**
     * Create a new pCliRoute object
     * @param string $name The name of the route
     * @param array|pMap $data The data retrieved from the settings
     * @since 1.0-elenor
     */
    public function __construct($name, $data) {
        $this->name = $name;
        $this->params = $data;
    }

    /**
     * Check whether the route matches the request
     * @param IAppRequest $request The request to check
     * @return boolean Returns true if the route matches the request, false
     *                      otherwise.
     * @since 1.0-elenor
     */
    public function match($request) {
        $ok = true;
        foreach($this->params as $key => $param){
            $subject = $request->params()->get($key);
            if(is_string($param)){
                $ok = preg_match('`' . $param . '`is', $subject);
            }else{
                $ok = $subject === $param;
            }
            if(!$ok){
                break;
            }
        }
        return $ok;
    }

    /**
     * Get the name of the route entry
     * @return string Returns the name
     * @since 1.0-elenor
     */
    public function name() {
        return $this->name;
    }
    
}