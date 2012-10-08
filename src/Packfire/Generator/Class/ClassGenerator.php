<?php
pload('packfire.collection.pList');
pload('packfire.pClassLoader');

/**
 * A class that generates class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.generator.class
 * @since 1.0-sofia
 */
class pClassGenerator {
    
    /**
     * The package that the class belongs in.
     * @var string
     * @since 1.0-sofia
     */
    private $package;
    
    /**
     * The name of the class
     * @var string
     * @since 1.0-sofia
     */
    private $name;
    
    /**
     * The classes that the class will extend from
     * @var pList
     * @since 1.0-sofia
     */
    private $extend;
    
    /**
     * The interfaces that the class will implement
     * @var pList
     * @since 1.0-sofia
     */
    private $implement;
    
    /**
     * The properties of the class
     * A collection of pClassProperty
     * @var pList
     * @since 1.0-sofia
     */
    private $properties;
    
    /**
     * The methods of the class
     * A collection of pClassMethod
     * @var pList
     * @since 1.0-sofia
     */
    private $methods;
    
    /**
     * The dependencies that the class
     * depends on.
     * @var pList
     * @since 1.0-sofia
     */
    private $dependencies; 
    
    /**
     * Flags if the property is abstract or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $abstract;
    
    /**
     * Create a new pClassGenerator object
     * @param string $name Name of the class
     * @param boolean $abstract (optional) Sets whether the class is abstract
     *                      or not. Defaults to false.
     * @since 1.0-sofia
     */
    public function __construct($name, $abstract = false){
        $this->name = $name;
        $this->abstract = $abstract;
        $this->dependencies = new pList();
        $this->methods = new pList();
        $this->implement = new pList();
        $this->extend = new pList();
        $this->properties = new pList();
    }
    
    /**
     * Get or set the name of the class to be generated
     * @param string $package (optional) Set the name of the class
     * @return string Returns the name of the class. 
     * @since 1.0-sofia
     */
    public function name($name = null){
        if(func_num_args() == 1){
            $this->name = $name;
        }
        return $name;
    }
    
    /**
     * Get or set the package that will contain this class
     * @param string $package (optional) Set the name of the package that 
     *                      the class will be stored in.
     * @return string Returns the package of the class. 
     * @since 1.0-sofia
     */
    public function package($package = null){
        if(func_num_args() == 1){
            $this->package = $package;
        }
        return $package;
    }
    
    /**
     * Make the class extend another class
     * @param string $class The name of the class to extend from
     * @return pClassGenerator Returns the instance itself for chaining.
     * @since 1.0-sofia
     */
    public function extend($class){
        $this->extend->add($class);
        return $this;
    }
    
    /**
     * Get the array of classes that this class extend from
     * @return pList Returns a list of class names
     * @since 1.0-sofia
     */
    public function extendList(){
        return $this->extend;
    }
    
    /**
     * Make the class implement an interface
     * @param string $interface The name of the interface to implement
     * @return pClassGenerator Returns the instance itself for chaining.
     * @since 1.0-sofia
     */
    public function implement($interface){
        $this->implement->add($interface);
        return $this;
    }
    
    /**
     * Get the array of interfaces that this class implement
     * @return pList Returns a list of interface names
     * @since 1.0-sofia
     */
    public function implementList(){
        return $this->implement;
    }
    
    /**
     * Add a package dependency that this class depends on
     * @param string $package The package that is depended on.
     * @return pClassGenerator Returns the instance itself for chaining.
     * @since 1.0-sofia
     */
    public function load($package){
        $this->dependencies->add($package);
        return $this;
    }
    
    /**
     * Get the array of packages that this class depends on
     * @return pList Returns a list of packages
     * @since 1.0-sofia
     */
    public function dependencies(){
        return $this->dependencies;
    }
    
    /**
     * Get the array of properties that this will have
     * @return pList Returns a list of class names
     * @since 1.0-sofia
     */
    public function properties(){
        return $this->properties;
    }
    
    /**
     * Get the array of methods that this class has
     * @return pMap Returns a pMap containing the methods
     * @since 1.0-sofia
     */
    public function methods(){
        return $this->methods;
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
     * Compile and create the PHP code for the class
     * @return string Returns a string that contains the PHP code for the class.
     * @since 1.0-sofia
     */
    public function compile(){
        $code = '';
        
        $code .= 'class ' . $this->name;
        if($this->extend->count() > 0){
            $code .= "\n extends "
                    . implode('', $this->extend->toArray());
        }
        if($this->implement->count() > 0){
            $code .= "\n implements "
                    . implode('', $this->implement->toArray());
        }
        $code .= " {\n";
        foreach($this->properties as $property){
            /* @var $property pClassProperty */
            $code .= '\n    ' . $property->compile() . "\n";
        }
        foreach($this->methods as $methods){
            /* @var $property pClassMethod */
            $code .= '\n    ' . $methods->compile() . "\n";
        }
        $code .= "}";
    }
    
    /**
     * Compile the class and generate the file based on the package
     * @since 1.0-sofia
     */
    public function generate(){
        $class = "<?php\n\n". $this->compile();
        $path = pClassLoader::reverseEngineer(
                $this->package . '.' . $this->name);
        $file = new pFile($path);
        $file->write($class);
    }
    
}