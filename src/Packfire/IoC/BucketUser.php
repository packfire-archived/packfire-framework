<?php
namespace Packfire\IoC;

use Packfire\IoC\IBucketUser;

/**
 * BucketUser class
 * 
 * A more concrete implementation of a bucket user
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IoC
 * @since 1.0-sofia
 */
abstract class BucketUser implements IBucketUser {
    
    /**
     * The bucket of services
     * @var ServiceBucket
     * @since 1.0-sofia
     */
    protected $services;
    
    /**
     * Set the service bucket to this user
     * @param ServiceBucket $bucket The bucket to let this user use.
     * @since 1.0-sofia 
     */
    public function setBucket($bucket){
        $this->services = $bucket;
    }
    
    /**
     * Copy the bucket from another user
     * @param IBucketUser $user The user to copy the IoC bucket from
     * @since 1.0-sofia
     */
    public function copyBucket($user){
        $this->services = $user->services;
    }
    
    /**
     * Get a service from the Service Bucket
     * @param string $service Name of the service to retrieve
     * @return object Returns the service object or null if no service found.
     * @since 1.0-sofia
     */
    public function service($service){
        if($this->services){
            return $this->services->pick($service);
        }else{
            return null;
        }
    }
    
}