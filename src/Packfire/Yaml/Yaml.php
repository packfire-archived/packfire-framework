<?php
namespace Packfire\Yaml;

use Packfire\IO\StreamReader;
use YamlParser;

/**
 * Yaml class
 * 
 * Provides functionalities to start working on a YAML stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Yaml
 * @since 1.0-sofia
 */
class Yaml {
    
    /**
     * The parser
     * @var YamlParser
     * @since 1.0-sofia
     */
    private $parser;
    
    /**
     * Create a new Yaml object
     * @param IInputStream $stream The input stream to read the document from
     * @since 1.0-sofia
     */
    public function __construct($stream){
        $reader = new StreamReader($stream);
        if($stream->seekable()){
            $stream->seek(0);
        }
        $this->parser = new YamlParser($reader);
    }
    
    /**
     * Read and parse the YAML document in the stream.
     * 
     * Note that YAML documents start with three hypens (---) and this method
     * will look for the start of the document. You can parse multiple documents
     * by calling this method multiple times.
     * 
     * @return array Returns an associated array of data parsed from the YAML
     *               document. 
     * @since 1.0-sofia
     */
    public function read(){
        $this->parser->findDocumentStart();
        return $this->parser->parse();
    }
    
    /**
     * Get the parser working on the YAML document
     * @return YamlParser Returns the parser
     * @since 1.0-sofia
     */
    public function parser(){
        return $this->parser;
    }
    
}
