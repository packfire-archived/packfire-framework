<?php
namespace Packfire\IoC;

/**
 * IServiceBucket interface
 * 
 * Service bucket abstraction
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IoC
 * @since 1.0-sofia
 */
interface IServiceBucket {
    
    /**
     * Put a service into the service bucket.
     * @param string $name The service identifier. Case-insensitive.
     * @param callback|Closure|object $resolver Either the object instance or
     *        the resolver function / method that will creates and return the
     *        object. Object returned must be an instance of the IService
     *        interface. The callback can accept the first parameter as the
     *        service bucket that requested for the execution.
     * @since 1.0-sofia
     */
    public function put($name, $resolver);
    
    /**
     * Pick out a service instance by the service identifier
     * @param string $serviceName The unique identifier of the service.
     *                            Case-insensitive.
     * @return object Returns the service instance, or NULL if not found.
     * @since 1.0-sofia 
     */
    public function pick($serviceName);
    
    /**
     * Check whether a particular service is available in the bucket
     * @param string $serviceName The name of the service to check for.
     * @return boolean Returns true if the service is available, false otherwise.
     * @since 1.0-sofia
     */
    public function contains($serviceName);
    
}