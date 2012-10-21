<?php
namespace Packfire\Route;

use Packfire\Route\IRoute;
use Packfire\Collection\ArrayList;
use Packfire\Validator\SerialValidator;
use Packfire\Validator\NumericValidator;
use Packfire\Validator\MatchValidator;
use Packfire\Validator\RegexValidator;
use Packfire\Validator\CallbackValidator;
use Packfire\Validator\EmailValidator;
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

    /**
     * Validate an array of data
     * @param ArrayList|array $rules The list of rules defined
     * @param ArrayList|array $data The data to be validated
     * @param Map|array $params (reference) The output parameters
     * @param boolean $validation (reference, optional) The validation boolean
     * @return boolean Returns true if validation is successful, false otherwise.
     * @since 1.1-sofia
     */
    protected function validateArray($rules, $data, &$params, &$validation = true){
        foreach($rules as $key => $rule){
            $param = null;
            if(isset($data[$key])){
                $param = $data[$key];
            }
            $validation = $this->validateParam($rule, $param, $data);
            if(!$validation){
                break;
            }
            $params[$key] = $param;
        }
        return $validation;
    }

    /**
     * Validate a value based on the given rule
     * @param string|ArrayList|array $rule The name of the validation rule(s)
     * @param mixed &$value The value to be validated
     * @return boolean Returns true if the validation succeeded, false otherwise.
     * @since 1.1-sofia
     */
    protected function validateParam($rules, &$value, &$data){
        if(is_array($rules)){
            $rules = new ArrayList($rules);
        }
        if(is_string($rules)){
            $rules = new ArrayList(array($rules));
        }

        // optional parameter and nothing supplied
        if($value === null){
            if($rules->contains('optional')){
                return true;
            }else{
                return false;
            }
        }

        $validator = new SerialValidator();
        $original = $value;
        foreach($rules as $rule){
            $slashPos = strpos($rule, '/');
            $options = '';
            if($slashPos !== false){
                $options = substr($rule, $slashPos + 1);
                $rule = substr($rule, 0, $slashPos);
            }
            switch($rule){
                case 'any':
                    break;
                case 'numeric':
                case 'number':
                case 'num':
                    $validator->add(new NumericValidator());
                    $value += 0;
                    break;
                case 'float':
                case 'real':
                case 'double':
                    $validator->add(new NumericValidator());
                    $validator->add(new CallbackValidator(function($value){
                        return is_float($value + 0);
                    }));
                    $value += 0;
                    break;
                case 'integer':
                case 'int':
                case 'long':
                    $validator->add(new NumericValidator());
                    $validator->add(new CallbackValidator(function($value){
                        return is_int($value + 0);
                    }));
                    $value += 0;
                    break;
                case 'min':
                    $min = $options + 0;
                    $validator->add(new CallbackValidator(function($x) use ($min){
                        return $x >= $min;
                    }));
                    break;
                case 'max':
                    $max = $options + 0;
                    $validator->add(new CallbackValidator(function($x) use ($max){
                        return $x <= $max;
                    }));
                    break;
                case 'bool':
                case 'boolean':
                    $validator->add(
                        new MatchValidator(array('true', 'false', '0', '1', 'on', 'off', 'y', 'n'))
                    );
                    $value = in_array($value, array('true', '1', 'on', 'y'), true);
                    break;
                case 'alnum':
                    $options = '/^[a-zA-Z0-9]+$/';
                    $validator->add(new RegexValidator($options));
                    break;
                case 'strmin':
                    $min = $options + 0;
                    $validator->add(new CallbackValidator(function($x) use ($min){
                        return strlen($x) >= $min;
                    }));
                    break;
                case 'strmax':
                    $max = $options + 0;
                    $validator->add(new CallbackValidator(function($x) use ($max){
                        return strlen($x) <= $max;
                    }));
                    break;
                case 'email':
                    $validator->add(new EmailValidator());
                    break;
                case 'alpha':
                    $options = '/^[a-zA-Z]+$/';
                    $validator->add(new RegexValidator($options));
                    break;
                case 'regex':
                    $validator->add(new RegexValidator($options));
                    break;
                case 'param':
                    $match = isset($data[$options])
                                    ? $data[$options] : null;
                    $validator->add(new MatchValidator($match));
                    break;
                case 'equal':
                case 'equals':
                case 'value':
                    $value = $options;
                    break;
                case 'optional':
                    break;
                default:
                    $validator->add(new MatchValidator($rule));
                    break;
            }
        }
        return $validator->validate($original);
    }


}