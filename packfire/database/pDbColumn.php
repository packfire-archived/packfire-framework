<?php

class pDbColumn {
    
    private $name;
    
    private $type;
    
    public function __construct($name, $type){
        $this->name = $name;
        $this->type = $type;
    }
    
    public function name(){
        return $this->name;
    }
    
    public function type(){
        return $this->type;
    }
    
}