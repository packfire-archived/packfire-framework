<?php
namespace Packfire\Collection\Sort;

/**
 * IComparable interface
 * 
 * An interface that allows comparing to another object.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection\Sort
 * @since 1.0-sofia
 */
interface IComparable {
    
    public function compareTo($another);
    
}
