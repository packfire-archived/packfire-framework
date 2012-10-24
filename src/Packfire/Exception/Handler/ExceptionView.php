<?php
namespace Packfire\Exception\Handler;

use Packfire\View\View;
use Packfire\Template\Mustache\TemplateFile;

/**
 * ExceptionView class
 * 
 * Exception display view
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception\Handler
 * @since 1.0-sofia
 */
class ExceptionView extends View {
    
    /**
     * The exception
     * @var Exception
     * @since 1.0-sofia
     */
    private $exception;
    
    /**
     * Create a new ExceptionView object
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
        $className = explode('\\', __CLASS__);
        $this->template(new TemplateFile(__DIR__ 
                . DIRECTORY_SEPARATOR
                . end($className) . '.html'));
        $this->define('title', 'Error ' . $this->exception->getCode());
        $this->define('file',  $this->exception->getFile());
        $this->define('line',  $this->exception->getLine());
        $this->define('debug', $this->service('config.app') ? $this->service('config.app')->get('app', 'debug') : false);
        $this->define('message',  $this->exception->getMessage());
        $this->define('stack', $this->exception->getTraceAsString());
    }
    
}