<?php
namespace Packfire\Route;

use Packfire\Route\IRoute;
use Packfire\Collection\Map;

/**
 * Route class
 *
 * A generic validatory route
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route
 * @since 1.1-sofia
 */
abstract class Route implements IRoute {

    /**
     * The name of the route
     * @var string
     * @since 1.1-sofia
     */
    protected $name;

    /**
     * The route parameters to check
     * @var Map
     * @since 1.1-sofia
     */
    protected $params;

    /**
     * The name of the controller class to route to
     * @var string
     * @since 1.1-sofia
     */
    protected $actual;

    /**
     * The parameter remapping data
     * @var Map
     * @since 2.0.0
     */
    protected $remap;
    
    /**
     * The parameter validation rules
     * @var Map
     * @since 2.0.0
     */
    protected $rules;
    
    /**
     * Create a new Route object
     * @param string $name The name of the route
     * @param array|Map $data The data retrieved from the settings
     * @since 2.0.0
     */
    public function __construct($name, $data){
        $this->name = $name;
        $this->params = new Map();
        $this->actual = $data->get('actual');
        $this->rules = new Map($data->get('params'));
        $this->remap = new Map($data->get('remap'));
    }

    /**
     * Get the parameters in this routing
     * @return Map Returns the parameters
     * @since 1.1-sofia
     */
    public function params(){
        return $this->params;
    }

    /**
     * Get the name of the controller class to route to
     * @return string Returns the controller class name
     * @since 1.1-sofia
     */
    public function actual(){
        return $this->actual;
    }

    /**
     * Get the name of the route entry
     * @return string Returns the name
     * @since 1.1-sofia
     */
    public function name() {
        return $this->name;
    }

    /**
     * Get the remapping data
     * @return Map Returns the remapping data
     * @since 2.0.0
     */
    public function remap() {
        return $this->remap;
    }

    /**
     * Get the validation rules
     * @return Map Returns the validation rules
     * @since 2.0.0
     */
    public function rules() {
        return $this->rules;
    }
    
    /**
     * Perform remapping of the parameters
     * @param array|IList $rules The remapping rules
     * @param array|IList $data The data to be remapped
     * @return array Returns the resulting remapped data
     * @since 2.0.0
     */
    protected function remapParam($rules, &$data){
        $result = array();
        foreach($rules as $key => $rule){
            $param = null;
            $index = null;
            if(is_array($rule)){
                $param = $data;
                $this->remapParam($rule, $param);
                $index = $key;
            }elseif(isset($data[$rule])){
                $param = $data[$rule];
                $index = $rule;
            }
            if($index){
                $result[$index] = $param;
            }
        }
        $data = $result;
    }
    
}