<?php

/**
 * A class property generator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.generator.class
 * @since 1.0-sofia
 */
class pClassProperty {
    
    /**
     * The access Level for the class property
     * Use constants from pClassAccess
     * @var integer
     * @since 1.0-sofia
     */
    private $access;
    
    /**
     * The name of the property
     * @var string
     * @since 1.0-sofia 
     */
    private $name;
    
    /**
     * The initial value to set to the property
     * @var mixed
     * @since 1.0-sofia
     */
    private $initial;
    
    /**
     * Flags if the property is static or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $static = false;
    
    /**
     * Create a new pClassProperty object
     * @param string $name Name of the property
     * @param integer $access (optional) The access level for the property, Use
     *                      one of the constants from pClassAccess.
     *                      Defaults to pClassAccess::ACCESS_PRIVATE.
     * @param boolean $static (optional) Sets whether the property is static
     *                  or not. Defaults to false.
     * @param mixed $initial (optional) Sets the initial value the property will
     *                  contain. Defaults to null.
     * @since 1.0-sofia
     */
    public function __construct($name, $access = pClassAccess::ACCESS_PRIVATE,
            $static = false, $initial = null){
        $this->name = $name;
        $this->access = $access;
        $this->static = $static;
        $this->initial = $initial;
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
     * Get the initial value set for the property
     * @return mixed Returns the initial value set
     * @since 1.0-sofia
     */
    public function initial(){
        return $this->initial;
    }
    
    /**
     * Get whether the property is static or not
     * @return string Returns a boolean, true if the property is static,
     *              false otherwise.
     * @since 1.0-sofia
     */
    public function isStatic(){
        return $this->static;
    }
    
    /**
     * Compile and create the PHP code for the property
     * @return string Returns a string that contains the PHP code for
     *                  the property.
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
            default:
                if(!$this->static){
                    $code .= 'var ';
                }
                break;
        }
        if($this->static){
            $code .= 'static ';
        }
        $name = $this->name;
        if(substr($name, 0, 1) != '$'){
            $name = '$' . $name;
        }
        $code .= ' ' . $name;
        if($this->initial !== null){
            if(is_bool($this->initial)){
                if($this->initial){
                    $code .= ' = true';
                }else{
                    $code .= ' = false';
                }
            }else{
                $code .= ' = ' . $this->initial. '';
            }
        }
        $code .= ';';
        return $code;
    }
    
}