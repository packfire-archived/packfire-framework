<?php
pload('packfire.view.pView');
pload('packfire.template.moustache.pMoustacheTemplate');

/**
 * Exception display view
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception.handler
 * @since 1.0-sofia
 */
class pExceptionPageView extends pView {
    
    /**
     * The exception
     * @var pException
     * @since 1.0-sofia
     */
    private $exception;
    
    /**
     * Create a new Exception Page view
     * @param Exception $exception The exception
     * @since 1.0-sofia
     */
    public function __construct($exception){
        parent::__construct();
        $this->exception = $exception;
    }
    
    /**
     * Create the page
     * @since 1.0-sofia 
     */
    protected function create() {
        $this->template(new pMoustacheTemplate(file_get_contents(dirname(__FILE__) 
                . DIRECTORY_SEPARATOR . 'error.html')));
        $this->define('title', 'Error ' . $this->exception->getCode());
        $this->define('file',  $this->exception->getFile());
        $this->define('line',  $this->exception->getLine());
        $this->define('debug', $this->service('config.app')->get('app', 'debug'));
        $this->define('message',  $this->exception->getMessage());
        $this->define('stack', $this->exception->getTraceAsString());
    }
    
}