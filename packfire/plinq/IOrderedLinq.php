<?php
pload('ILinq');

/**
 * A generic ordered LINQ with the thenBy() and thenByDesc() implementation
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
interface IOrderedLinq extends ILinq {
    
    public function thenBy($field);
    
    public function thenByDesc($field);
    
}