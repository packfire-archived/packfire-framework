<?php
namespace Packfire\Template;

use Packfire\Template\ITemplate;

/**
 * ITemplateFile interface
 * 
 * Interfacing for file templates
 * 
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template
 * @since 1.1-sofia
 */
interface ITemplateFile extends ITemplate {
    
    /**
     * Create a new file template object
     * @param File|string $file The file or pathname to file
     * @since 1.1-sofia
     */
    public function __construct($file);
    
}