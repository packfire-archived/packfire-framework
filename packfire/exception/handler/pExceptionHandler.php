<?php
pload('IExceptionHandler');
pload('pExceptionPageView');

/**
 * An exception handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception
 * @since 1.0-sofia
 */
class pExceptionHandler implements IExceptionHandler {

    /**
     * Handle the exception
     * @param Exception $exception The exception to handle
     * @since 1.0-sofia
     */
    public function handle($exception) {
        $view = new pExceptionPageView($exception);
        echo $view->render();
        exit;
    }
    
}