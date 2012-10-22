<?php
namespace Packfire\Exception;

use Packfire\Exception\Exception;

/**
 * InvalidArgumentException class
 * 
 * Invalid argument exception
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception
 * @since 1.0-sofia
 */
class InvalidArgumentException extends Exception {
    
    /**
     * Create a new InvalidArgumentException object
     * @param string $method The class method that is raising this exception
     * @param string $argument The argument that has an invalid value
     * @param string $expectation The expecting value
     * @param string $given (optional) The actual value that was given
     * @param string $code (optional) The error code if any
     * @since 1.0-sofia
     */
    public function __construct($method, $argument, $expectation, $given = null, $code = null) {
        $message = sprintf('%s() expects $%s to be %s.' . (func_num_args() > 3 ? ' %s was given instead.' : ''),
                $method, $argument, $expectation, (string)$given);
        parent::__construct($message, $code);
    }
    
}