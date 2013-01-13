<?php
namespace Packfire\Core;

/**
 * Provides multiple method calls
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Core
 * @since 2.0.8
 */
class CallSequence {
    
    /**
     * The reference for inserting the values.
     * @var object
     * @since 2.0.8
     */
    private $reference;
    
    /**
     * The pipeline of functions to run
     * @var array
     * @since 2.0.8
     */
    private $pipeline = array();
    
    /**
     * Create a new CallSequence object
     * @since 2.0.8
     */
    public function __construct(){
        // create a unique reference object
        $this->reference = new \stdClass();
    }
    
    /**
     * Get the reference for value parameter
     * @return object Returns the object for referencing
     * @since 2.0.8
     */
    public function value(){
        return $this->reference;
    }
    
    /**
     * Add a callback into the call sequence
     * @param callback|Closure $func The callback to process the value
     * @param array $parameters (optional) The array of parameters to
     *          pass into the callback.
     * @param boolean $return (optional) Set whether the value is set to the
     *          returning value of this callback. Defaults to true.
     * @since 2.0.8
     */
    public function add($func, $parameters = array(), $return = true){
        $this->pipeline[] = array($func, $parameters, $return);
    }
    
    /**
     * Clear the call sequence
     * @since 2.0.8
     */
    public function clear(){
        $this->pipeline = array();
    }
    
    /**
     * Process a value with the call sequence
     * @param mixed $value The value to pass through the call sequence
     * @return mixed Returns the final resulting value
     * @since 2.0.8
     */
    public function process($value){
        foreach($this->pipeline as $stage){
            list($callback, $parameters, $return) = $stage;
            $keys = array_keys($parameters, $this->reference, true);
            foreach($keys as $key){
                $parameters[$key] = $value;
            }
            if($return){
                $value = call_user_func_array($callback, $parameters);
            }else{
                call_user_func_array($callback, $parameters);
            }
        }
        return $value;
    }
    
}