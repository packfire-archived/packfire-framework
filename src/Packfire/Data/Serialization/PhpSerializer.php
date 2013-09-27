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

use Packfire\Data\Serialization\ISerializer;
use Packfire\Data\Serialization\ISerializable;
use Packfire\IO\IInputStream;

/**
 * Perform serialization through PHP's default serialize() and unserialize()
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Data\Serialization
 * @since 1.0-sofia
 */
class PhpSerializer implements ISerializer
{
    /**
     * Serialize the data using PHP's default serialize() method.
     * @param IOutputStream       $stream The stream to write the serialized data to
     * @param ISerializable|mixed $data   The data to be serialized.
     * @since 1.0-sofia
     */
    public function serialize($stream, $data = null)
    {
        if (func_num_args() == 1) {
            $data = $stream;
            if ($data instanceof ISerializable) {
                $data = $data->serialize();
            }

            return serialize($data);
        } else {
            if ($data instanceof ISerializable) {
                $data = $data->serialize();
            }
            $buffer = serialize($data);
            $stream->write($buffer);
        }
    }

    /**
     * Deserialize the serialized data from the stream
     * @param  IInputStream $stream|string The stream to read the serialized data from
     * @return mixed        Returns the data unserialized
     * @since 1.0-sofia
     */
    public function deserialize($stream)
    {
        $buffer = '';
        if ($stream instanceof IInputStream) {
            while ($data = $stream->read(1024)) {
                $buffer .= $data;
            }
        } else {
            $buffer = $stream;
        }

        return unserialize($buffer);
    }
}
