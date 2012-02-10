<?php

interface IList extends ArrayAccess, IIterable {
    
    public function add($item);
    
    public function contains($item);
    
    public function clear();
    
    public function get($index);
    
    public function remove($item);
    
    public function removeAt($index);
    
    public function indexOf($item);
    
    public function indexesOf($item);
    
    public function lastIndexOf($item);
    
}