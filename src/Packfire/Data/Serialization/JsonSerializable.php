<?php
namespace Packfire\Data\Serialization;

use \JsonSerializable as IJsonSerializable;

/**
 * JsonSerializable class
 * 
 * Makes JsonSerializable compatible with older PHP versions
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.data.serialization
 * @since 1.0-sofia
 */
abstract class JsonSerializable implements ISerializable, IJsonSerializable {
    
    public function serialize() {
        return $this->jsonSerialize();
    }
    
}