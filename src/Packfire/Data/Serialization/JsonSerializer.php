<?php
pload('ISerializer');
pload('ISerializable');

/**
 * pJsonSerializer class
 * 
 * Provide JSON serialization and deserialization services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.data.serialization
 * @since 1.0-sofia
 */
class pJsonSerializer implements ISerializer {
    
    /**
     * Serialize the data into JSON
     * @param IOutputStream $stream The stream to write the serialized data to
     * @param ISerializable|mixed $data The data to be serialized.
     * @since 1.0-sofia
     */
    public function serialize($stream, $data = null){
        if(func_num_args() == 1){
            $data = $stream;
            if($data instanceof ISerializable){
                $data = $data->serialize();
            }
            return json_encode($data);
        }else{
            if($data instanceof ISerializable){
                $data = $data->serialize();
            }
            $buffer = json_encode($data);
            $stream->write($buffer);
        }
    }
    
    /**
     * Deserialize the serialized data from the stream
     * @param IInputStream $stream The stream to read the serialized data from
     * @return mixed Returns the data unserialized
     * @since 1.0-sofia
     */
    public function deserialize($stream){
        $buffer = '';
        if($stream instanceof IInputStream){
            while($data = $stream->read(1024)){
                $buffer .= $data;
            }
        }else{
            $buffer = $stream;
        }
        return json_decode($buffer);
    }
    
}