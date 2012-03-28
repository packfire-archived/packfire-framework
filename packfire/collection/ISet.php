<?php

/**
 * Mathematical set interface operations
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection
 * @since 1.0-sofia
 */
interface ISet {
    
    public function union($set);
    
    public function intersect($set);
    
    public function difference($set);
    
    public function complement($set);
    
}