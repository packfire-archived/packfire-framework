<?php

class pQueue extends pList{
    
    public function enqueue($item){
        $this->add($item);
    }
    
    public function dequeue(){
        $value = null;
        if($this->count() > 0){
            $value = array_shift($this->array);
        }
        return $value;
    }
    
}
