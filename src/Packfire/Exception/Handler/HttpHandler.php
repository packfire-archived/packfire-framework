<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Exception\Handler;

use Packfire\Exception\Handler\IHandler;

/**
 * An exception handler for HTTP
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception\Handler
 * @since 1.0-sofia
 */
class HttpHandler implements IHandler
{
    /**
     * The view package name to load
     * @var string
     * @since 1.1-sofia
     */
    private $view;

    /**
     * The debugger to use
     * @var \Packfire\Debugger\Debugger
     * @since 2.1.0
     */
    private $debugger;

    /**
     * Flags if debugging is enabled
     * @var boolean
     * @since 2.1.0
     */
    private $debug = false;

    /**
     * The logger
     * @var \Psr\Log\LoggerInterface
     * @since 2.1.0
     */
    private $logger;

    /**
     * Create a new HttpHandler object
     * @param string $view The package class name of the view to load
     * @since 1.1-sofia
     */
    public function __construct($view = null)
    {
        $this->view = $view;
    }

    /**
     * Handle the exception
     * @param Exception $exception The exception to handle
     * @since 1.0-sofia
     */
    public function handle($exception)
    {
        if ($this->debugger) {
            $this->debugger->exception($exception);
        }

        $class = $this->view;
        if (!$class) {
            $class = 'Packfire\Exception\Handler\ExceptionView';
        }
        $view = new $class($exception, $this->debug);
        echo $view->render();
        flush();

        if (!$this->debug && $this->logger) {
            $this->logger->critical(
                    '"' . $exception->getMessage() .
                        '" at ' . $exception->getFile() . ':'
                        . $exception->getLine(),
                    array(
                        'exception'     => get_class($exception),
                        'code'          => $exception->getCode()
                    )
                );
        }
    }

    public function __invoke($container)
    {
        if (isset($container['debugger'])) {
            $this->debugger = $container['debugger'];
            $this->debug = $this->debugger->enabled();
        }
        if (isset($container['logger'])) {
            $this->logger = $container['logger'];
        }

        return $this;
    }

}
