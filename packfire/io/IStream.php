<?php

/**
 * Stream for reading/writing operations to and from a stream
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.io
 * @since 1.0-sofia
 */
interface IStream {
    
    /**
     * Open the stream for access
     * @since 1.0-sofia 
     */
    public function open();
    
    /**
     * Close the stream and release resources
     * @since 1.0-sofia 
     */
    public function close();
    
}
