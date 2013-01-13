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
 * Interfacing for file templates
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template
 * @since 1.1-sofia
 */
interface ITemplateFile {
    
    /**
     * Create a new file template object
     * @param File|string $file The file or pathname to file
     * @since 1.1-sofia
     */
    public function __construct($file);
    
}