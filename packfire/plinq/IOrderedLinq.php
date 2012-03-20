<?php
pload('ILinq');

/**
 * IOrderedLinq Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since version-created
 */
interface IOrderedLinq extends ILinq {
    
    public function thenBy($field);
    
    public function thenByDesc($field);
    
}