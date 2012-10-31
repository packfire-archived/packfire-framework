<?php
namespace Packfire\Controller;

/**
 * Error class
 * 
 * bucket for controller errors
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Controller
 * @since 2.0.0
 */
class Error {
    
    /**
     * The collection of errors
     * @var array
     * @since 2.0.0
     */
    protected $errors;
    
    /**
     * Create a new Error object
     * @param array|ArrayList $errors (optional) Initialize the container with errors
     * @since 2.0.0
     */
    public function __construct($errors = array()){
        $this->errors = $errors;
    }
    
    /**
     * Check if any error exists
     * @return boolean Returns true if there's at least one error,
     *                false if there's none.
     * @since 2.0.0
     */
    public function exists(){
        return count($this->errors) > 0;
    }
    
    /**
     * Get the collection of errors
     * @return array Returns the collection
     * @since 2.0.0
     */
    public function errors(){
        return $this->errors;
    }
    
    /**
     * Set an error to a target field
     * @param string $target The name of the target field
     * @param \Exception|string $exception The exception that occurred or the error message
     * @since 2.0.0
     */
    public function set($target, $exception){
        if($exception instanceof \Exception){
            $exception = $exception->getMessage();
        }
        if(isset($this->errors[$target])){
            $this->errors[$target] = array_merge($this->errors[$target],
                    (array)$exception);
        }else{
            $this->errors[$target] = (array)$exception;
        }
    }
    
    /**
     * Clear the bucket of errors
     * @since 2.0.0
     */
    public function clear(){
        $this->errors = array();
    }
    
}