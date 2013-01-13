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
use Packfire\Data\Serialization\XmlSerializer;
use Packfire\Response\IResponseFormat;

/**
 * Provides an XML response to the client
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Response
 * @since 1.0-sofia
 */
class XmlResponse extends HttpResponse implements IResponseFormat {
    
    /**
     * Create a new XmlResponse object
     * @param mixed $object The object that will be encoded and sent to the
     *                      client
     * @since 1.0-sofia
     */
    public function __construct($object){
        parent::__construct();
        $this->headers()->add('Content-Type', 'application/xml');
        if(is_string($object)){ // probably already encoded
            $this->body($object); 
        }else{
            $serializer = new XmlSerializer();
            $this->body('<?xml version="1.0" encoding="UTF-8" ?>'
                    . "\n" . $serializer->serialize($object));
        }
    }
    
}