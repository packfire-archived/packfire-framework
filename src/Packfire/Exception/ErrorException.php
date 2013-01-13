<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Exception;

use Packfire\Exception\Exception;

/**
 * An exception encapsulating a native PHP error
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception
 * @since 1.0-sofia
 */
class ErrorException extends Exception {
    
    public function setCode($code){
        $this->code = $code;
    }
    
    public function setLine($line){
        $this->line = $line;
    }
    
    public function setFile($file){
        $this->file = $file;
    }
    
}