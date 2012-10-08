<?php
namespace Packfire\Data\Serialization;

/**
 * ISerializable interface
 * 
 * An abstraction for object that can be serialized.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Data\Serialization
 * @since 1.0-sofia
 */
interface ISerializable {
    
    public function serialize();
    
}