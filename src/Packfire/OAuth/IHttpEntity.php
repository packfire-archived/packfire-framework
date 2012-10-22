<?php
namespace Packfire\OAuth;

/**
 * IHttpEntity interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\OAuth
 * @since 1.1-sofia
 */
interface IHttpEntity {
    
    public function oauth($key, $value = null);
    
}