<?php
pload('ILinqWorkerQuery');

abstract class pLinqWorkerQuery implements ILinqWorkerQuery {
    
    private $worker;
    
    public function __construct($worker){
        $this->worker = $worker;
    }
    
    public function worker(){
        return $this->worker;
    }
    
}