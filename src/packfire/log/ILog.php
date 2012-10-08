<?php

/**
 * Log file abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.log
 * @since 1.0-sofia
 */
interface ILog {
    
    /**
     * Write a log entry to the log file
     * @param array|Map $data The data of the log entry
     * @since 1.0-sofia
     */
    public function write($data);
    
}