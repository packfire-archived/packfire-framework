<?php
Packfire::load('IInputStream');
Packfire::load('IOutputStream');

/**
 * A stream that can be written and read
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io
 * @since 1.0-sofia
 */
interface IIOStream extends IInputStream, IOutputStream {
    
    
}