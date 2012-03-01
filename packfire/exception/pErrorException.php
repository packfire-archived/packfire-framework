<?php
pload('pException');

/**
 * An exception encapsulating a native PHP error
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception
 * @since 1.0-sofia
 */
class pErrorException extends pException {
    
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