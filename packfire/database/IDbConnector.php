<?php

/**
 * IDbConnector interface
 * 
 * A database connector interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
interface IDbConnector {
    
    public function __construct($config);
    
    public function database();
    
}