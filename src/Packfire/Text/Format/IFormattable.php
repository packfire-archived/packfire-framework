<?php
namespace Packfire\Text\Format;

/**
 * IFormattable interface
 * 
 * An interface indicating that the object can be formatted into a string
 * of a certain format
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Text\Format
 * @since 2.0.4
 */
interface IFormattable {
    
    public function format($format);
    
}