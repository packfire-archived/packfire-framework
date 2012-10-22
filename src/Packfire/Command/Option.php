<?php
namespace Packfire\Command;

/**
 * Option class
 *
 * An option rule
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Command
 * @since 2.0.0
 */
class Option implements IOption {
    
    /**
     * The full name entered
     * @var string
     * @since 2.0.0
     */
    private $name;
    
    /**
     * An array containing the compiled names
     * @var array
     * @since 2.0.0
     */
    private $names;
    
    /**
     * Determine if the option has value
     * @var boolean
     * @since 2.0.0
     */
    private $hasValue;
    
    /**
     * Determine if the option is required
     * @var boolean
     * @since 2.0.0
     */
    private $isRequired;
    
    /**
     * The help text for this string
     * @var string
     * @since 2.0.0
     */
    private $help;
    
    /**
     * The callback that will handle the value
     * @var Closure|callback
     * @since 2.0.0
     */
    private $callback;
    
    /**
     * Create a new Option object
     * @param string $name The option names. Multiple names can be entered
     *              separated by a vertical bar '|'. If the option require a
     *              value
     * @param string $callback The callback to handle values retrieved
     * @param string $help (optional) The help text
     * @since 2.0.0
     */
    public function __construct($name, $callback, $help = null){
        $this->name = $name;
        $this->isRequired = substr($name, 0, 1) == '!';
        if($this->isRequired){
            $name = substr($name, 0, strlen($name) - 1);
        }
        $this->hasValue = substr($name, -1) == '=';
        if($this->hasValue){
            $name = substr($name, 0, strlen($name) - 1);
        }
        $this->names = explode('|', $name);
        $this->callback = $callback;
        $this->help = $help;
    }
    
    /**
     * Get the original name of the options
     * @return string Returns the original name
     * @since 2.0.0
     */
    public function name(){
        return $this->name;
    }
    
    /**
     * Get the flag if the option is required
     * @return boolean Returns if the option is required
     * @since 2.0.0
     */
    public function required(){
        return $this->isRequired;
    }
    
    /**
     * Get the flag if the option has a value
     * @return boolean Returns if the option has a value
     * @since 2.0.0
     */
    public function hasValue(){
        return $this->hasValue;
    }
    
    /**
     * Check if a option name matches the option names in this option
     * @param string $name The name to check against
     * @return boolean Returns true if the option name match, false otherwise.
     * @since 2.0.0
     */
    public function matchName($name){
        return in_array($name, $this->names);
    }
    
    /**
     * Get the help text of this option
     * @return string Returns the help text
     * @since 2.0.0
     */
    public function help(){
        return $this->help;
    }
    
    /**
     * Parse the value by calling the calback with the value
     * @param string $value The option value
     * @since 2.0.0
     */
    public function parse($value){
        call_user_func($this->callback, $value);
    }
    
}