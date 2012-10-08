<?php
namespace Packfire\Data\Serialization;

/**
 * ISerializer interface
 * 
 * Serializer abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Data\Serialization
 * @since 1.0-sofia
 */
interface ISerializer {
    
    public function serialize($stream, $data = null);
    
    public function deserialize($stream);
    
}