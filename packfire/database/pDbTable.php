<?php
pload('packfire.collection.pMap');

class pDbTable {
    
    protected $name;
    
    protected $columns;
    
    public function __construct(){
        $this->columns = pMap();
    }
    
}