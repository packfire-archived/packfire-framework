<?php

/**
 * IAppResponse interface
 * 
 * Abstraction for application response
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
interface IAppResponse {
    
    /**
     * Get the internal response
     * @return IAppResponse Returns the internal response
     * @since 1.0-sofia
     */
    public function response();
    
}