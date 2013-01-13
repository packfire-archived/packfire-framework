<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Debugger\Output;

use Packfire\Collection\ArrayList;
use Packfire\Collection\Map;
use Packfire\Debugger\IOutput;
use Packfire\Template\Mustache\TemplateFile;

/**
 * Provides Client-side GUI debugging console output
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Debugger\Output
 * @since 1.0-sofia
 */
class Console implements IOutput {
    
    /**
     * The lines to output
     * @var ArrayList
     * @since 1.0-sofia
     */
    private $lines;
    
    /**
     * The types of log that has been entered
     * @var Map
     * @since 1.0-sofia
     */
    private $types;
    
    /**
     * Create a new Output object
     * @since 1.0-sofia 
     */
    public function __construct(){
        $this->lines = new ArrayList();
        $this->types = new Map();
    }

    /**
     * Write the log message to the console
     * @param string $message The message to write to the console
     * @param string $value (optional) The secondary value to the message
     * @param string $type (optional) The type of message written, 
     *              defaults to 'log'.
     * @since 1.0-sofia
     */
    public function write($message, $value = null, $type = 'log') {
        if($type == 'dump'){
            $message = '<pre>' . $message . '</pre>';
        }
        $this->lines->add(
                    array('type' => $type, 'message' => $message, 'value' => $value)
                );
        if(!$this->types->keyExists($type)){
            $this->types->add($type, 0);
        }
        $this->types->add($type, $this->types[$type] + 1);
    }
    
    /**
     * Output the console
     * @since 1.0-sofia 
     */
    public function output(){
        $template = new TemplateFile(__DIR__ . '/ConsoleTemplate.html');
        
        $template->fields()->add('lines', $this->lines);
        
        $tabs = array();
        foreach($this->types as $type => $count){
            $tabs[] = array('type' => $type, 'count' => $count);
        }
        $template->fields()->add('tabs', $tabs);
        
        $template->fields()->add('totalCount', $this->lines->count());
        
        echo $template->parse();
    }
    
}