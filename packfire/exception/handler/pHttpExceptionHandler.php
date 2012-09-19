<?php
pload('IExceptionHandler');
pload('pExceptionPageView');
pload('packfire.exception.handler.pErrorHandler');

/**
 * An exception handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception.handler
 * @since 1.0-sofia
 */
class pHttpExceptionHandler extends pBucketUser implements IExceptionHandler {
    
    public function __construct(){
        $errorhandler = new pErrorHandler($this);
        set_error_handler(array($errorhandler, 'handle'), E_ALL);
    }

    /**
     * Handle the exception
     * @param Exception $exception The exception to handle
     * @since 1.0-sofia
     */
    public function handle($exception) {
        $view = new pExceptionPageView($exception);
        $view->copyBucket($this);
        echo $view->render();
        exit;
    }
    
}