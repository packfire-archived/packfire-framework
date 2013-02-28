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

/**
 * Interfacing for all template objects
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template
 * @since 1.0-sofia
 */
interface ITemplate {
    
    /**
     * Create a new template object
     * @param string $template The template to use
     * @since 1.0-sofia
     */
    public function __construct($template);
    
    /**
     * Parses the template fields into the template
     * @return string Returns the parsed template
     * @since 1.0-sofia
     */
    public function parse();
    
    /**
     * Get the template fields
     * @return Map Returns the template fields hash map
     * @since 1.0-sofia
     */
    public function fields();
    
    /**
     * Set fields to the template
     * @param mixed $set The fields to be set
     * @since 1.0-sofia
     */
    public function set($set);
    
    /**
     * Returns the parsed output
     * @return string Returns the parsed template
     * @since 2.0.9
     */
    public function __toString();
    
}