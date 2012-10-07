<?php
namespace Packfire\Collection;
use IIterable;
use ISet;

/**
 * IList interface
 * 
 * A List interface.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
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
    
    public function first();
    
    public function last();
    
}