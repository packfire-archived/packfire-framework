<?php
pload('ISerializer');

/**
 * pXmlSerializer class
 * 
 * Provide XML serialization and deserialization services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.data.serialization
 * @since 1.0-sofia
 */
class pXmlSerializer implements ISerializer {
    
    public function serialize($stream, $data) {
        
    }
    
    public function deserialize($stream) {
        
    }
    
    /**
     * Serialize the data and write the XML output into the stream
     * @param IOutputStream $stream The stream to write the XML to
     * @param mixed $data The data to write
     * @param string $block The node name of the block
     * @param pMap|array $attributes (optional) The tag attributes of the node
     * @since 1.0-sofia
     * @static
     */
    private static function writeXml($stream, $data, $block, $attributes = array()) {
        if(is_object($data)){
            $block = 'class:' . get_class($object);
            $data = get_object_vars($object);
        }
        $stream->write('<' . $block);
        foreach($attributes as $key => $value){
            $stream->write(' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '"');
        }
        $stream->write('>');
        self::writeItemXml($stream, $data);
        $stream->write('</' . $block . '>');
    }

    /**
     * Serialize and write an XML item into a stream
     * @param IOutputStream $stream The stream to write to
     * @param mixed $item The item to be serialized
     * @since 1.0-sofia
     */
    private static function writeItemXml($stream, $item) {
        if (is_object($item) || is_array($item)) {
            foreach($item as $key => $value){
                $attributes = array();
                if (is_numeric($key)) {
                    $attributes['key'] = $key;
                    $key = 'node';
                }
                if(is_scalar($value) && !is_string($value)){
                    $attributes['type'] = gettype($value);
                }
                self::writeXml($stream, $value, $key, $attributes);
            }
        } else {
            if(is_string($item)){
                $stream->write(htmlspecialchars($item, ENT_QUOTES));
            }elseif(is_boolean($item)){
                $stream->write($item ? 'true' : 'false');
            }else{
                $stream->write($item);
            }
        }
    }


    
}