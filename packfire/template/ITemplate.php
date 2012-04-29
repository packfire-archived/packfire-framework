<?php

/**
 * ITemplate Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.template
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
     * @return pMap Returns the template fields hash map
     * @since 1.0-sofia
     */
    public function fields();
    
    /**
     * Set fields to the template
     * @param mixed $set The fields to be set
     * @since 1.0-sofia
     */
    public function set($set);
    
}