<?php

interface ISet {
    
    public function union($set);
    
    public function intersect($set);
    
    public function difference($set);
    
    public function complement($set);
    
}