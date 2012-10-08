<?php
namespace Packfire\Collection;

use IList;

/**
 * IMap interface
 * 
 * A Hash Map interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Collection
 * @since 1.0-sofia
 */
interface IMap extends IList {
    
    public function keyExists($key);
    
    public function keys();
    
    public function values();
    
}