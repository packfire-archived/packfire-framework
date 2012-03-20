<?php
pload('ILinqQuery');

interface ILinqWorkerQuery extends ILinqQuery {
    
    public function worker();
    
}
