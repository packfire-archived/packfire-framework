<?php
pload('IExceptionHandler');
pload('pErrorException');

/**
 * An exception handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception
 * @since 1.0-sofia
 */
class pExceptionHandler implements IExceptionHandler {
    
    public function handleError($errno, $errstr, $errfile, $errline, $errcontext) {
        $e = new pErrorException();
        $e->setCode($errno);
        $e->setLine($errline);
        $e->setMessage($errstr);
        $e->setFile($errfile);
        $e->setContext($errcontext);
        $this->handleException($e);
    }

    public function handleException($exception) {
        
    }

}