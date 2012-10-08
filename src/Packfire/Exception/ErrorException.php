<?php
namespace Packfire\Exception;

use Exception;

/**
 * ErrorException class
 * 
 * An exception encapsulating a native PHP error
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
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