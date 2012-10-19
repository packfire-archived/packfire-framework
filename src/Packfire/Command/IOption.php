<?php
namespace Packfire\Command;

/**
 * IOption interface
 *
 * An option abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Command
 * @since 2.0.0
 */
interface IOption {
    
    public function parse($value);
    
}