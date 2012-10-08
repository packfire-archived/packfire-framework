<?php
namespace Packfire\Exception\Handler;

/**
 * IHandler interface
 * 
 * An exception handler abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Exception\Handler
 * @since 1.0-sofia
 */
interface IHandler {
    
    public function handle($exception);
    
}