<?php

class pStack extends pList {
    
    public function push($item){
        $this->add($item);
    }
    
    public function pop(){
        $value = null;
        if($this->count() > 0){
            $value = array_pop($this->array);
        }
        return $value;
    }
    
}