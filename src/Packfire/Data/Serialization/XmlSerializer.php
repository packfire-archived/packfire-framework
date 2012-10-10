<?php
namespace Packfire\Data\Serialization;

use Packfire\Data\Serialization\ISerializer;
use Packfire\Data\Serialization\ISerializable;
use Packfire\IO\IInputStream;
use Packfire\Text\TextStream;
use Packfire\Exception\MissingDependencyException;

/**
 * XmlSerializer class
 * 
 * Provide XML serialization and deserialization services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Data\Serialization
 * @since 1.0-sofia
 */
class XmlSerializer implements ISerializer {
    
    /**
     * Serialize data into XML string and write to output stream
     * @param IOutputStream $stream The stream to write the output data to
     * @param mixed $data The data to write 
     * @since 1.0-sofia
     */
    public function serialize($stream, $data = null) {
        if(func_num_args() == 1){
            $data = $stream;
            $stream = new TextStream();
            if($data instanceof ISerializable){
                $data = $data->serialize();
            }
            self::writeXml($stream, $data, 'root');
            $stream->seek(0);
            return $stream->read($stream->length());
        }else{
            if($data instanceof ISerializable){
                $data = $data->serialize();
            }
            self::writeXml($stream, $data, 'root');
        }
    }
    
    /**
     * Deserialize a XML input stream into data
     * @param IInputStream $stream The stream to read the data from 
     * @return mixed Returns the original data
     * @since 1.0-sofia
     */
    public function deserialize($stream) {
        $xml = '';
        if($stream instanceof IInputStream){
            $xml = $stream->read($stream->length());
        }else{
            $xml = $stream;
        }
        if(!class_exists('DOMDocument')){
            throw new MissingDependencyException('DOM is required by XmlSerializer but extension not enabled.');
        }
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        if($doc->childNodes->length > 0){
            $data = self::processNode($doc->childNodes->item(0));
            return reset($data);
        }
        return null;
    }
    
    /**
     * Process a DOM node
     * @param DOMNode $node The node to process
     * @return mixed Returns the data processed from the node
     * @since 1.0-sofia
     */
    private static function processNode($node){
        $object = array();
        $name = $node->nodeName;
        if($node->childNodes->length == 1
                && $node->childNodes->item(0) instanceof DOMText){
            $key = $node->attributes->getNamedItem('key');
            $type = $node->attributes->getNamedItem('type');
            $value = $node->childNodes->item(0)->wholeText;
            if($type){
                settype($value, $type->value);
            }
            $key = $key ? (string)$key->value : $name;
            $object[$key] = $value;
        }elseif($node instanceof DOMNode){
            foreach($node->childNodes as $child){
                $object += self::processNode($child);
            }
            if(substr($name, 0, 6) == 'class.'){
                $class = substr($name, 6);
                if(class_exists($class)){
                    $data = $object;
                    $object = new $class();
                    foreach($data as $key => $value){
                        $object->$key = $value;
                    }
                }
            }
            $key = $node->attributes->getNamedItem('key');
            $key = $key ? (string)$key->value : $name;
            $object = array($key => $object);
        }
        return $object;
    }
    
    /**
     * Serialize the data and write the XML output into the stream
     * @param IOutputStream $stream The stream to write the XML to
     * @param mixed $data The data to write
     * @param string $block The node name of the block
     * @param Map|array $attributes (optional) The tag attributes of the node
     * @since 1.0-sofia
     * @static
     */
    private static function writeXml($stream, $data, $block, $attributes = array()) {
        if(is_object($data)){
            $block = 'class.' . get_class($data);
            $data = get_object_vars($data);
        }
        $stream->write('<' . $block);
        foreach($attributes as $key => $value){
            $stream->write(' ' . $key . '="'
                    . htmlspecialchars($value, ENT_QUOTES) . '"');
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
            }elseif(is_bool($item)){
                $stream->write($item ? 'true' : 'false');
            }else{
                $stream->write($item);
            }
        }
    }


    
}