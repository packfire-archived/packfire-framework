<?php
namespace Packfire\IO;

use Packfire\IO\IInputStream;
use Packfire\IO\IOutputStream;

/**
 * IIOStream interface
 * 
 * A stream that can be written and read
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IO
 * @since 1.0-sofia
 */
interface IIOStream extends IInputStream, IOutputStream {
    
}