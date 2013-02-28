<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Template;

use Packfire\Template\ITemplate;
use Packfire\Collection\ArrayList;
use Packfire\Collection\Map;
use Packfire\Text\Regex\Regex;

/**
 * Provides operations on template parsing.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template
 * @since 1.0-sofia
 */
class Template implements ITemplate {

    /**
     * The template tag opening key 
     * @since 1.0-sofia
     */
    const KEY_OPEN = '{';
    
    /**
     * The template tag closing key 
     * @since 1.0-sofia
     */
    const KEY_CLOSE = '}';
    
    /**
     * The template containing the tags
     * @var string
     * @since 1.0-sofia
     */
    private $template;
    
    /**
     * The template fields
     * @var Map
     * @since 1.0-sofia
     */
    private $fields;
    
    /**
     * Create a new Template object
     * @param string $template The template to use
     * @since 1.0-sofia
     */
    public function __construct($template){
        $this->template = $template;
        $this->fields = new Map();
    }
    
    /**
     * Get the template fields
     * @return Map Returns the template fields hash map
     * @since 1.0-sofia
     */
    public function fields(){
        return $this->fields;
    }

    /**
     * Parses the template fields into the template
     * @return string Returns the parsed template
     * @since 1.0-sofia
     */
    public function parse(){
        $result = $this->template;
        foreach($this->fields as $key => $v){
            $key = self::KEY_OPEN . $key . self::KEY_CLOSE;
            if(strpos($result, $key) !== false){
                $result = str_replace($key, $v, $result);
            }
        }
        
        return $result;
    }

    /**
     * Get the list of tokens found in the template
     * @return ArrayList Returns the list of tokens
     * @since 1.0-sofia
     */
    public function tokens(){
        $tokens = new ArrayList();
        $matches = array();
        $i = preg_match_all('`' . Regex::escape(self::KEY_OPEN) .
                '([a-zA-Z0-9\.]+)' . Regex::escape(self::KEY_CLOSE) .
                '`is', $this->template, $matches, PREG_SET_ORDER);
        if($i > 0){
            foreach($matches as $m){
                $tokens->add($m[1]);
            }
        }
        return $tokens;
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
    
    public function __toString() {
        return $this->parse();
    }
    
}
