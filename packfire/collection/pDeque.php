<?php

class pDeque extends pQueue {
    
    public function enqueueFront($item){
        array_unshift($this->array, $item);
    }
    
    public function dequeueBack(){
        $value = null;
        if($this->count() > 0){
            $value = array_pop($this->array);
        }
        return $value;
    }
    
}