<?php
namespace Packfire\Template;

use ITemplateFile;
use Packfire\IO\File\File;
use Template;

/**
 * TemplateFile class
 * 
 * A simple template file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template
 * @since 1.1-sofia
 */
class TemplateFile extends Template implements ITemplateFile {
    
    /**
     * Create a new TemplateFile object
     * @param File|string $file The file or pathname to the file
     * @since 1.1-sofia
     */
    public function __construct($file){
        if($file instanceof File){
            $file = $file->pathname();
        }
        parent::__construct(file_get_contents($file));
    }
    
}