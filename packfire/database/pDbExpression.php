<?php

/**
 * pDbExpression Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
class pDbExpression {
    
    private $expression;
    
    public function __construct($expression){
        $this->expression = $expression;
    }
    
    public function expression(){
        return $this->expression;
    }
    
}