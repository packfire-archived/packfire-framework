<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\IoC;

/**
 * A user of the ServiceBucket
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IoC
 * @since 1.0-sofia
 */
interface IBucketUser {
    
    /**
     * Set the bucket to use for this bucket user
     * @param ServiceBucket $bucket The service bucket to use
     * @since 1.0-sofia 
     */
    public function setBucket($bucket);
    
    /**
     * Copy the bucket from another user
     * @param IBucketUser $user The user to copy the IoC bucket from
     * @since 1.0-sofia
     */
    public function copyBucket($user);
    
    /**
     * Get a service to use
     * @param string $service Name of the service to use
     * @return mixed Returns the service
     * @since 1.0-sofia 
     */
    public function service($service);
    
}