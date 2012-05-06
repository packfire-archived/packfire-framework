<?php

/**
 * pClassMethod Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.generator.class
 * @since 1.0-sofia
 */
class pClassMethod {
    
    /**
     * The access Level for the class method
     * Use constants from pClassAccess
     * @var integer
     * @since 1.0-sofia
     */
    private $access;
    
    /**
     * Flags if the property is abstract or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $abstract = false;
    
    /**
     * Flags if the property is static or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $static = false;
    
    /**
     * The name of the method
     * @var string
     * @since 1.0-sofia 
     */
    private $name;
    
    /**
     * The PHP code inside the method
     * @var string
     * @since 1.0-sofia
     */
    private $code;
    
    /**
     * The list of arguments for the method
     * @var pList
     * @since 1.0-sofia
     */
    private $arguments;
    
    /**
     * Create a new pClassMethod object
     * @param string $name The name of the method
     * @param array|pList $arguments (optional) The list of arguments of 
     *                      the method.
     * @param string $code (optional) The PHP code within the method.
     *                      Defaults to an empty string.
     * @param integer $access (optional) The access level for the method, Use
     *                      one of the constants from pClassAccess.
     *                      Defaults to pClassAccess::ACCESS_PRIVATE.
     * @param boolean $static (optional) Sets whether the method is static
     *                      or not. Defaults to false.
     * @param boolean $abstract (optional) Sets whether the method is abstract
     *                      or not. Defaults to false.
     * @since 1.0-sofia
     */
    public function __construct($name, $arguments = null, $code = '',
            $access = pClassAccess::ACCESS_PUBLIC,
            $static = false, $abstract = false){
        $this->name = $name;
        if($arguments){
            $this->arguments = new pList($arguments);
        }
        $this->code = $code;
        $this->access = $access;
        $this->static = $static;
        $this->abstract = $abstract;
    }
    
    /**
     * Get the access level of the property.
     * Returns one of the constants from pClassAccess.
     * @return integer Returns the access level represented by
     *                  constants from pClassAccess.
     * @since 1.0-sofia
     */
    public function access(){
        return $this->access;
    }
    
    /**
     * Get the name of the property
     * @return string Returns the name of the property
     * @since 1.0-sofia
     */
    public function name(){
        return $this->name;
    }
    
    /**
     * Get whether the method is static or not
     * @return string Returns a boolean, true if the method is static,
     *              false otherwise.
     * @since 1.0-sofia
     */
    public function isStatic(){
        return $this->static;
    }
    
    /**
     * Get whether the method is abstract or not
     * @return string Returns a boolean, true if the method is abstract,
     *              false otherwise.
     * @since 1.0-sofia
     */
    public function isAbstract(){
        return $this->abstract;
    }
    
    /**
     * Compile and create the PHP code for the method
     * @return string Returns a string that contains the PHP code for
     *                  the method.
     * @since 1.0-sofia
     */
    public function compile(){
        $code = '';
        switch($this->access){
            case pClassAccess::ACCESS_PUBLIC:
                $code .= 'public ';
                break;
            case pClassAccess::ACCESS_PRIVATE:
                $code .= 'private ';
                break;
            case pClassAccess::ACCESS_PROTECTED:
                $code .= 'protected ';
                break;
        }
        if($this->static){
            $code .= 'static ';
        }
        
        if($this->abstract){
            $code .= 'abstract ';
        }
        
        $code .= 'function ' . $this->name;
        // the arguments
        $args = $this->arguments->toArray();
        array_map(array($this, 'argumentMapper'), $args);
        $code .= '(' . implode(', ', $args) . ')';
        if($this->abstract){
            $code .= ';';
        }else{
            $code .= "{\n" . $this->code . "\n}";
        }
        return $code;
    }
    
    /**
     * Callback for array_map to walk through each of the arguments
     * to make sure that they all have the variable dollar sign at the front.
     * @param string $arg The argument passed in by array_map
     * @return string Returns the processed argument
     * @since 1.0-sofia
     */
    private function argumentMapper($arg){
        if(substr($arg, 0, 1) != '$'){
                $arg = '$' . $arg;
        }
        return $arg;
    }
    
}