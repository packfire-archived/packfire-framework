<?php
namespace Packfire\Linq;

use ILinq;

/**
 * IOrderedLinq interface
 * 
 * A generic ordered LINQ with the thenBy() and thenByDesc() implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Linq
 * @since 1.0-sofia
 */
interface IOrderedLinq extends ILinq {
    
    public function thenBy($field);
    
    public function thenByDesc($field);
    
}