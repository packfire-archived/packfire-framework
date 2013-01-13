<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Response;

use Packfire\Application\Http\Response as HttpResponse;
use Packfire\Yaml\YamlWriter;
use Packfire\Text\TextStream;
use Packfire\Response\IResponseFormat;

/**
 * A response class that returns YAML format
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Response
 * @since 1.1-sofia
 */
class YamlResponse extends HttpResponse implements IResponseFormat {
    
    /**
     * Create a new YamlResponse object
     * @param mixed $object The array or object that will be responded to the
     *                      client with
     * @since 1.1-sofia
     */
    public function __construct($object){
        parent::__construct();
        $this->headers()->add('Content-Type', 'application/x-yaml');
        if(is_string($object)){ // probably already encoded
            $this->body($object); 
        }else{
            $textStream = new TextStream();
            $writer = new YamlWriter($textStream);
            if(is_object($object)){
                $object = (array)$object;
            }
            $writer->write($object);
            $textStream->seek(0);
            $this->body($textStream->read($textStream->length()));
        }
    }
    
}