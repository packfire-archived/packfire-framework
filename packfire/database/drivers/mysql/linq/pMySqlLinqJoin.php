<?php

/**
 * pMySqlLinqJoin Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.divers.mysql.linq
 * @since 1.0-sofia
 */
class pMySqlLinqJoin {
    
    private $target;
    private $source;
    private $innerKey;
    private $outerKey;
    private $selector;
    
    public function __construct($target, $source, $innerKey, $outerKey, $selector){
        $this->source = $source;
        $this->target = $target;
        $this->innerKey = $innerKey;
        $this->outerKey = $outerKey;
        $this->selector = $selector;
    }
    
    public function create(){
        $join = '';
        if($this->selector){
            $join .= $this->selector .' ';
        }
        $join .= 'JOIN ' . $this->source . ' ON ';
        $join .= $this->target . '.' . $this->innerKey;
        $join .= ' = ';
        $join .= $this->source . '.' . $this->outerKey;
        
        return $join;
    }
    
}