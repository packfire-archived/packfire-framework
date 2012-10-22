<?php
namespace Packfire\Exception\Handler;

use Packfire\Exception\Handler\IHandler;

/**
 * CliHandler
 * 
 * An exception handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception\Handler
 * @since 1.0-elenor
 */
class CliHandler implements IHandler {

    /**
     * Handle the exception
     * @param Exception $exception The exception to handle
     * @since 1.0-sofia
     */
    public function handle($exception) {
        echo 'Error: ' . $exception->getMessage() . "\n";
        echo $exception->getTraceAsString();
        exit;
    }
    
}