<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Data\Serialization;

use \JsonSerializable as IJsonSerializable;

/**
 * Makes JsonSerializable compatible with older PHP versions
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Data\Serialization
 * @since 1.0-sofia
 */
abstract class JsonSerializable implements ISerializable, IJsonSerializable
{
    public function serialize()
    {
        return $this->jsonSerialize();
    }
}
