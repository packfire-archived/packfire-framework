<?php
pload('IErrorHandler');
pload('packfire.exception.pErrorException');

/**
 * A PHP error handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception.handler
 * @since 1.0-sofia
 */
class pErrorHandler implements IErrorHandler {
    
    /**
     * The exception handler to interact with
     * @var IExceptionHandler
     * @since 1.0-sofia
     */
    private $handler;
    
    /**
     * Create a new pErrorHandler
     * @param IExceptionHandler $handler 
     * @since 1.0-sofia
     */
    public function __construct($handler) {
        $this->handler = $handler;
    }
    
    /**
     * Handle the error
     * @param mixed $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline 
     * @since 1.0-sofia
     */
    public function handle($errno, $errstr, $errfile, $errline) {
        $e = new pErrorException($errstr);
        $e->setCode($errno);
        $e->setLine($errline);
        $e->setFile($errfile);
        $this->handler->handle($e);
    }
    
}