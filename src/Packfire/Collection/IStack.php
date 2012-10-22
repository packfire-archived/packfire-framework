<?php
namespace Packfire\Collection;

/**
 * IStack interface
 * 
 * Stack abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
interface IStack {
    
     public function push($item);
     
     public function pop();
     
     public function top();
     
}