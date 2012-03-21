<?php
pload('pLinqWorkerQuery');

class pLinqSelectQuery extends pLinqWorkerQuery {
    
    public function run($collection) {
       $result = array();
       $worker = $this->worker();
       foreach($collection as $element){
           $result[] = $worker($element);
       }
       return $result;
    }
}