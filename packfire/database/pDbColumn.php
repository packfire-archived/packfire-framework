<?php

/**
 * pDbColumn class
 * 
 * A database table column
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
class pDbColumn {
    
    /**
     * Name of the column
     * @var string
     * @since 1.0-sofia
     */
    private $name;
    
    /**
     * Data type of the column
     * @var string
     * @since 1.0-sofia
     */
    private $type;
    
    /**
     * Create a new pDbColumn object
     * @param string $name Name of the column
     * @param string $type Data type of the column
     * @since 1.0-sofia
     */
    public function __construct($name, $type){
        $this->name = $name;
        $this->type = $type;
    }
    
    /**
     * Get the name of the column
     * @return string Returns the name of the column
     * @since 1.0-sofia
     */
    public function name(){
        return $this->name;
    }
    
    /**
     * Get the data type of the column
     * @return string Returns the data type of the column
     * @since 1.0-sofia
     */
    public function type(){
        return $this->type;
    }
    
}