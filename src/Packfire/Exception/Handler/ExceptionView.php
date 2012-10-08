<?php
namespace Packfire\Exception\Handler;

use Packfire\View\View;
use Packfire\Template\Moustache\Template;

/**
 * ExceptionView class
 * 
 * Exception display view
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception.handler
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
        $this->template(new Template(file_get_contents(__DIR__ 
                . DIRECTORY_SEPARATOR . basename(__CLASS__) . '.html')));
        $this->define('title', 'Error ' . $this->exception->getCode());
        $this->define('file',  $this->exception->getFile());
        $this->define('line',  $this->exception->getLine());
        $this->define('debug', $this->service('config.app') ? $this->service('config.app')->get('app', 'debug') : false);
        $this->define('message',  $this->exception->getMessage());
        $this->define('stack', $this->exception->getTraceAsString());
    }
    
}