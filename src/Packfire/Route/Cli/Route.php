<?php
namespace Packfire\Route\Cli;

use Packfire\Route\Route as CoreRoute;
use Packfire\Collection\Map;

/**
 * Route class
 * 
 * A command-line interface route entry
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route\Cli
 * @since 1.0-elenor
 */
class Route extends CoreRoute {
    
    /**
     * Create a new Route object
     * @param string $name The name of the route
     * @param array|Map $data The data retrieved from the settings
     * @since 1.0-elenor
     */
    public function __construct($name, $data) {
        if(!($data instanceof Map)){
            $data = new Map($data);
        }
        parent::__construct($name, $data);
        $this->rules = $data->get('params');
        $this->remap = $data->get('remap');
    }

    /**
     * Check whether the route matches the request
     * @param Packfire\Application\Cli\Request $request The request to check
     * @return boolean Returns true if the route matches the request, false
     *                      otherwise.
     * @since 1.0-elenor
     */
    public function match($request) {
        $validation = true;
        $requestParams = new Map($request->params());
        $params = array();
        if($this->rules){
            $validation = $this->validateArray($this->rules, $requestParams->toArray(), $params);
        }
        if($validation){
            $this->remapParam($this->remap, $params);
            $this->params = new Map($params);
        }
        return $validation;
    }
    
}