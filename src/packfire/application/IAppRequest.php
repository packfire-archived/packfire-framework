<?php

/**
 * IAppRequest interface
 * 
 * Generic application request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
interface IAppRequest {
    
    /**
     * Get the method of the application request
     * @return string Returns the method identifier
     * @since 1.1-sofia
     */
    public function method();
    
    /**
     * Get the parameters of the request
     * @return pList|array Returns the parameters
     * @since 1.0-sofia 
     */
    public function params();
    
}