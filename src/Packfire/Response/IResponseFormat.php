<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Response;

use Packfire\Application\IAppResponse;

/**
 * Defines a response format
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Response
 * @since 1.1-sofia
 */
interface IResponseFormat extends IAppResponse {
    
    /**
     * Create a new instance of IResponseFormat
     * @param mixed $object The object to format and respond
     * @since 1.1-sofia
     */
    public function __construct($object);
    
}