<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Log;

use Packfire\IO\File\File;
use Packfire\IO\File\IFile;
use Packfire\Template\Template;

/**
 * A log file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Log
 * @since 1.0-sofia
 */
class Log implements ILog, IFile {
    
    /**
     * The file to write to
     * @var File
     * @since 1.0-sofia
     */
    private $file;
    
    /**
     * The format for each log entry
     * @var string
     * @since 1.0-sofia
     */
    private $format = '{datetime}: [{context}] {message}';
    
    /**
     * Create a new Log object
     * @param File|string $file The log file to write to
     * @since 1.0-sofia
     */
    public function __construct($file){
        if(is_string($file)){
            $file = new File($file);
        }
        $this->file = $file;
    }
    
    /**
     * Write a log entry to the log file
     * @param array|Map $data The data of the log entry
     * @since 1.0-sofia
     */
    public function write($data) {
        $template = new Template($this->format);
        $template->fields()->append($data);
        $this->file->append($template->parse() . "\n");
    }
    
    /**
     * Get or set the format of writing the log entries
     * @param string $format Set the new format for the log entries to use
     * @return string Returns the format of writing the log entries
     * @since 1.0-sofia
     */
    public function format($format = null){
        if(func_num_args() == 1){
            $this->format = $format;
        }
        return $this->format;
    }
    
    /**
     * Get the pathname to the log file
     * @return string Returns the pathname of the log file.
     * @since 1.0-sofia
     */
    public function pathname(){
        return $this->file->pathname();
    }
    
}