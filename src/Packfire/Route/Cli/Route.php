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
     * The parameters remapping
     * @var Map
     * @since 1.0-elenor
     */
    private $remap;
    
    /**
     * Create a new pCliRoute object
     * @param string $name The name of the route
     * @param array|Map $data The data retrieved from the settings
     * @since 1.0-elenor
     */
    public function __construct($name, $data) {
        $this->name = $name;
        $this->actual = $data->get('actual');
        $this->params = $data->get('params');
        $this->remap = $data->get('remap');
    }

    /**
     * Check whether the route matches the request
     * @param pCliAppRequest $request The request to check
     * @return boolean Returns true if the route matches the request, false
     *                      otherwise.
     * @since 1.0-elenor
     */
    public function match($request) {
        $validation = true;
        $requestParams = new Map($request->params());
        $params = array();
        $this->remap($requestParams);
        if($this->params){
            $validation = $this->validateArray($this->params, $requestParams->toArray(), $params);
        }
        if($validation){
            $this->params = new Map($params);
        }
        return $validation;
    }
    
    /**
     * Perform remapping of arguments
     * @param Map $params The parameters to be remapped
     * @since 1.0-elenor
     */
    private function remap($params){
        if($this->remap){
            foreach($this->remap as $source => $target){
                if($params->keyExists($source)){
                    $value = $params->get($source);
                    $params->removeAt($source);
                    $params->add($target, $value);
                }
            }
        }
    }
    
}