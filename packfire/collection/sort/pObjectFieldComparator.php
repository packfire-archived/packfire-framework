<?php

class pObjectFieldComparator implements IComparator{
    
    private $field;
    
    public function __construct($field){
        $this->field = $field;
    }

    function compare($a, $b) {
        if ($this->access($a) == $this->access($b)) {
            return 0;
        }
        return ($this->access($a) < $this->access($b)) ? -1 : 1;
    }
    
    private function access($object){
        if(is_array($object)){
            return $object[$this->field];
        }else{
            return $object->{$this->field};
        }
    }

}