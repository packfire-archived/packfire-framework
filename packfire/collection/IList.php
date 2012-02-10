<?php

interface IList extends ArrayAccess, IIterable, ISet {
    
    public function add($item);
    
    public function contains($item);
    
    public function clear();
    
    public function get($index);
    
    public function removeAll($list);
    
    public function remove($item);
    
    public function removeAt($index);
    
    public function indexOf($item);
    
    public function indexesOf($item);
    
    public function lastIndexOf($item);
    
    public function prepend($list);
    
    public function append($list);
    
}