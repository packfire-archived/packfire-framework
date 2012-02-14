<?php
pload('IStream');

/**
 * Input Stream for reading operations from a stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io
 * @since 1.0-sofia
 */
interface IInputStream extends IStream {
    
    public function read($length);
    
    public function seek($position);
    
    public function tell();
    
    public function seekable();
    
    public function length();
    
}