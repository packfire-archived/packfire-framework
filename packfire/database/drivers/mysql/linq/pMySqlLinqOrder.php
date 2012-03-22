<?php

/**
 * pMySqlLinqOrder Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.divers.mysql.linq
 * @since 1.0-sofia
 */
class pMySqlLinqOrder {
    
    private $field;
    
    private $descending;
    
    public function __construct($field, $descending = false){
        $this->descending = $descending;
        $this->field = $field;
    }
    
    public function create(){
        return $this->field . ($this->descending ? ' DESC': '');;
    }
    
}