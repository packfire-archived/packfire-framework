<?php

/**
 * ServiceLoader abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.ioc
 * @since 1.0-sofia
 */
interface IServiceLoader {
    
    /**
     * Load the service
     * @since 1.0-sofia 
     */
    public function load();
    
}