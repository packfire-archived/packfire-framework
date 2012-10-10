<?php
namespace Packfire\Collection;

/**
 * ISet interface
 * 
 * Mathematical set interface operations
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
interface ISet {
    
    public function union($set);
    
    public function intersect($set);
    
    public function difference($set);
    
    public function complement($set);
    
}