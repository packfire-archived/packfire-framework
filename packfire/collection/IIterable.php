<?php

interface IIterable extends Countable, IteratorAggregate {
    
    public function iterator();
    
}