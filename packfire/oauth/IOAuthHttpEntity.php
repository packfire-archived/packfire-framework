<?php

/**
 * IOAuthHttpEntity interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.oauth
 * @since 1.1-sofia
 */
interface IOAuthHttpEntity {
    
    public function oauth($key, $value = null);
    
}