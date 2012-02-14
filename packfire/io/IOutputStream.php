<?php
pload('IStream');

/**
 * Output Stream for writing operations to a stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io
 * @since 1.0-sofia
 */
interface IOutputStream extends IStream {
    
    public function write($data, $offset = null, $length = null);
    
    public function flush();
    
}