<?php
namespace Packfire\IoC;

use IServiceBucket;
use ILoadable;

/**
 * BucketLoader class
 * 
 * Provides interfacing for service loading
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IoC
 * @since 1.0-elenor
 */
abstract class BucketLoader implements IServiceBucket, ILoadable {
    
    /**
     * The service bucket to be decorated
     * @var IServiceBucket
     * @since 1.0-elenor
     */
    protected $bucket;
    
    /**
     * Create a new BucketLoader object
     * @param IServiceBucket $bucket The bucket to be decorated
     * @since 1.0-elenor
     */
    public function __construct($bucket){
        $this->bucket = $bucket;
    }
    
    /**
     * Put a service into the service bucket.
     * @param string $name The service identifier. Case-insensitive.
     * @param callback|Closure|object $resolver Either the object instance or
     *        the resolver function / method that will creates and return the
     *        object. Object returned must be an instance of the IService
     *        interface. The callback can accept the first parameter as the
     *        service bucket that requested for the execution.
     * @since 1.0-elenor
     */
    public function put($name, $resolver){
        $this->bucket->put($name, $resolver);
    }
    
    /**
     * Pick out a service instance by the service identifier
     * @param string $serviceName The unique identifier of the service.
     *                            Case-insensitive.
     * @return object Returns the service instance, or NULL if not found.
     * @since 1.0-elenor 
     */
    public function pick($serviceName){
        return $this->bucket->pick($serviceName);
    }
    
    /**
     * Check whether a particular service is available in the bucket
     * @param string $serviceName The name of the service to check for.
     * @return boolean Returns true if the service is available, false otherwise.
     * @since 1.0-elenor
     */
    public function contains($serviceName){
        return $this->bucket->contains($serviceName);
    }
    
}