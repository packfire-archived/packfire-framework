<?php

/**
 * A MySQL LINQ Order statement
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.divers.mysql.linq
 * @since 1.0-sofia
 */
class pMySqlLinqOrder {
    
    /**
     * The field to sort
     * @var string
     * @since 1.0-sofia
     */
    private $field;
 
    /**
     * Flags whether the order is descending or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $descending;
    
    /**
     * Create a new pMySqlLinqOrder object
     * @param string $field The field to sort
     * @param boolean $descending (optional) Sets whether the order is descending
     *                or not, defaults to false.
     * @since 1.0-sofia
     */
    public function __construct($field, $descending = false){
        $this->descending = $descending;
        $this->field = $field;
    }
    
    /**
     * Create the statement
     * @return string
     * @since 1.0-sofia
     */
    public function create(){
        return $this->field . ($this->descending ? ' DESC': '');;
    }
    
}