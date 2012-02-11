<?php
Packfire::load('packfire.io.pInputStreamReader');
Packfire::load('pYamlParser');

class pYaml {
    
    /**
     *
     * @var pInputStreamReader
     */
    private $reader;
    
    public function __construct($stream){
        $this->reader = new pInputStreamReader($stream);
        $this->reader->stream()->seek(0);
    }
    
    public function read(){
        $parser = new pYamlParser($this->reader);
        $parser->parse();
    }
    
}
