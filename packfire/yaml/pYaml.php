<?php
Packfire::load('packfire.io.pInputStreamReader');
Packfire::load('pYamlParser');

/**
 * Provides functionalities to start working on a YAML stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.yaml
 * @since 1.0-sofia
 */
class pYaml {
    
    /**
     * The parser
     * @var pYamlParser
     * @since 1.0-sofia
     */
    private $parser;
    
    /**
     * Create a YAML working pYaml class
     * @param IInputStream $stream The input stream to read the document from
     * @since 1.0-sofia
     */
    public function __construct($stream){
        $reader = new pInputStreamReader($stream);
        $reader->stream()->seek(0);
        $this->parser = new pYamlParser($reader);
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
     * @return pYamlParser Returns the parser
     * @since 1.0-sofia
     */
    public function parser(){
        return $this->parser;
    }
    
}
