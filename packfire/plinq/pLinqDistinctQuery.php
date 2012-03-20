<?php

class pLinqDistinctQuery implements ILinqQuery {
    
    public function run($collection) {
        $result = array_unique($collection);
        return $result;
    }
    
}