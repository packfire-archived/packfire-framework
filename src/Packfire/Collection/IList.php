<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Collection;

use Packfire\Collection\IIterable;
use Packfire\Collection\ISet;

/**
 * A List interface.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
interface IList extends \ArrayAccess, IIterable, ISet {
    
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