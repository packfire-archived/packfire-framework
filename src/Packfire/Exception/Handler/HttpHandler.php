<?php
namespace Packfire\Exception\Handler;

use Packfire\Exception\Handler\IHandler;
use Packfire\Exception\Handler\ExceptionView;
use Packfire\IoC\BucketUser;

/**
 * HttpHandler class
 * 
 * An exception handler for HTTP
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception\Handler
 * @since 1.0-sofia
 */
class HttpHandler extends BucketUser implements IHandler {
    
    /**
     * The view package name to load
     * @var string
     * @since 1.1-sofia
     */
    private $view;
    
    /**
     * Create a new HttpHandler object
     * @param string $view The package class name of the view to load
     * @since 1.1-sofia
     */
    public function __construct($view = null){
        $this->view = $view;
    }

    /**
     * Handle the exception
     * @param Exception $exception The exception to handle
     * @since 1.0-sofia
     */
    public function handle($exception) {
        $this->service('debugger')->exception($exception);
        
        $class = $this->view;
        if(!$class){
            $class = 'ExceptionView';
        }
        $view = new $class($exception);
        $view->copyBucket($this);
        echo $view->render();
        flush(); 
        
        if(!$this->service('debugger')->enabled() && $this->service('logger')){
            $this->service('logger')->log(
                    '"' . $exception->getMessage() .
                        '" at ' . $exception->getFile() . ':' 
                        . $exception->getLine(),
                    get_class($exception) . ' ' . $exception->getCode()
                );
        }
    }
    
}