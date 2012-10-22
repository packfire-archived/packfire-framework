<?php
namespace Packfire\Database;

/**
 * Expression class
 * 
 * A database expression to be inserted directly during binding
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
class Expression {
    
    /**
     * The expression
     * @var mixed
     * @since 1.0-sofia
     */
    private $expression;
    
    /**
     * Create a new Expression object
     * @param mixed $expression The expression
     * @since 1.0-sofia
     */
    public function __construct($expression){
        $this->expression = $expression;
    }
    
    /**
     * Get the database expression
     * @return mixed Returns the expression
     * @since 1.0-sofia
     */
    public function expression(){
        return $this->expression;
    }
    
}