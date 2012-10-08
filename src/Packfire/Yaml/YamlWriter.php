<?php
namespace Packfire\Yaml;

use Packfire\Collection\ArrayList;

/**
 * pYamlWriter class
 * 
 * A YAML writer class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Yaml
 * @since 1.0-sofia
 */
class YamlWriter {
    
    /**
     * The stream to write data to
     * @var IOutputStream
     * @since 1.0-sofia
     */
    private $stream;
    
    /**
     * Create a new pYamlWriter object
     * @param IOutputStream $stream The stream to write data to
     * @since 1.0-sofia
     */
    public function __construct($stream){
        $this->stream = $stream;
    }
    
    /**
     * Write a YAML data document to the stream
     * @param array|ArrayList $data The YAML data document to write
     * @since 1.0-sofia
     */
    public function write($data){
        $this->stream->write("\n---\n");
        $this->writeData($data);
        $this->stream->write("...\n");
    }
    
    /**
     * Checks if an array is sequential
     * @param array $data The array to be checked
     * @return boolean Returns true if the array is sequential, false otherwise.
     * @since 1.0-sofia
     */
    private function isSequential($data){
        return array_values($data) === $data;
    }
    
    /**
     * Write data to the stream
     * @param ArrayList|array $data The data to be written to the stream
     * @param integer $indentation (optional) The indentation of the data block
     * @param boolean $firstIndent (optional) Set whether the first item is
     *              indented or not.
     * @since 1.0-sofia
     */
    private function writeData($data, $indentation = 0, $firstIndent = true){
        if($data instanceof ArrayList){
            $data = $data->toArray();
        }
        $spaceIndent = str_repeat(' ', $indentation * 2);
        if($this->isSequential($data)){
            foreach($data as $value){
                if($firstIndent){
                    $this->stream->write($spaceIndent);
                }else{
                    $firstIndent =  true;
                }
                if(is_array($value) || $value instanceof ArrayList){
                    $this->stream->write('- ');
                    $this->writeData($value, $indentation + 1, false);
                }else{
                    $this->stream->write('- ' . $this->formatValue($value, $indentation) . "\n");
                }
            }
        }else{
            foreach($data as $key => $value){
                if($firstIndent){
                    $this->stream->write($spaceIndent);
                }else{
                    $firstIndent =  true;
                }
                if(is_array($value) || $value instanceof ArrayList){
                    $this->stream->write($key .  ":\n");
                    $this->writeData($value, $indentation + 1);
                }else{
                    $this->stream->write($key .  ': ' . $this->formatValue($value, $indentation) . "\n");
                }
            }
        }
    }
    
    /**
     * Formats the value
     * @param mixed $value The value to be formatted
     * @param integer $indentation The level of indentation
     * @return string Returns the formatted value
     * @since 1.0-sofia
     */
    private function formatValue($value, $indentation){
        switch(gettype($value)){
            case 'string':
                if(strlen($value) > 60){
                    $space = str_repeat(' ', $indentation * 2);
                    $value = "\n" . $space . wordwrap(
                                str_replace("\n", "\n\n" . $space, $value), 60,
                             "\n" . $space, true) . "\n";
                }else{
                    $value = '"' . str_replace(array('\\','"'),
                            array('\\\\', '\\"'), $value).  '"';
                }
                break;
            case 'boolean':
                $value = $value ? 'true' : 'false';
                break;
            case 'NULL':
                $value = 'null';
                break;
        }
        return $value;
    }
    
}