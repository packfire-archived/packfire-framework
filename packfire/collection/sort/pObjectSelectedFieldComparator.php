<?php

class pObjectSelectedFieldComparator implements IComparator{
    
    private $fieldSelector;
    
    public function __construct($fieldSelector){
        $this->fieldSelector = $fieldSelector;
    }

    function compare($a, $b) {
        if ($this->access($a) == $this->access($b)) {
            return 0;
        }
        return ($this->access($a) < $this->access($b)) ? -1 : 1;
    }
    
    private function access($object){
        $func = $this->fieldSelector;
        return $func($object);
    }

}