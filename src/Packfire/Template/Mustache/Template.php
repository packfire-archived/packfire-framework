<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Template\Mustache;

use Packfire\Template\ITemplate;
use Packfire\Template\Mustache\Bridge;
use Packfire\Collection\ArrayList;
use Packfire\Collection\Map;

/**
 * A Packfire template that uses the Mustache
 * logic-less template rendering engine
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template\Mustache
 * @since 1.0-sofia
 */
class Template implements ITemplate {
    
    /**
     * The fields to parse
     * @var mixed
     * @since 1.0-sofia
     */
    private $fields;
    
    /**
     * The mustache parser
     * @var Mustache
     * @since 1.0-sofia
     */
    private $parser;
    
    /**
     * Create a new Template object
     * @param string $template The template to be parsed
     * @since 1.0-sofia
     */
    public function __construct($template) {
        $this->parser = new Bridge($template);
        $this->fields = new Map();
    }

    /**
     * Get the fields set to render into the template
     * @return mixed Returns the fields and their values
     * @since 1.0-sofia
     */
    public function fields() {
        return $this->fields;
    }

    /**
     * Parses the template fields into the template
     * @return string Returns the parsed template
     * @since 1.0-sofia
     */
    public function parse() {
        return $this->parser->parameters($this->fields)->render();
    }
    
    /**
     * Set fields to the template
     * @param mixed $set The fields to be set
     * @since 1.0-sofia
     */
    public function set($set){
        if(is_object($set) && !($set instanceof ArrayList)){
            $set = get_object_vars($set);
        }
        if(is_array($set) || $set instanceof ArrayList){
            foreach($set as $key => $value){
                $this->fields->add($key, $value);
            }
        }
    }
    
}