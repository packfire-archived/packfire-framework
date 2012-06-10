<?php
pload('ISerializer');

/**
 * pPhpSerializer class
 * 
 * Perform serialization through PHP's default serialize() and unserialize()
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.data.serialization
 * @since 1.0-sofia
 */
class pPhpSerializer implements ISerializer {
    
    /**
     * Serialize the data using PHP's default serialize() method.
     * @param IOutputStream $stream The stream to write the serialized data to
     * @param ISerializable|mixed $data The data to be serialized.
     * @since 1.0-sofia
     */
    public static function serialize($stream, $data){
        if($data instanceof ISerializable){
            $data = $data->serialize();
        }
        $buffer = serialize($data);
        $stream->write($buffer);
    }
    
    /**
     * Deserialize the serialized data from the stream
     * @param IInputStream $stream The stream to read the serialized data from
     * @return mixed Returns the data unserialized
     * @since 1.0-sofia
     */
    public static function deserialize($stream){
        $buffer = '';
        while($data = $stream->read(1024)){
            $buffer .= $data;
        }
        return unserialize($buffer);
    }
    
}