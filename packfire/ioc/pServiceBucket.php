<?php
pload('packfire.collection.pMap');
pload('IBucketUser');

/**
 * A bucket containing all the services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.ioc
 * @since 1.0-sofia
 */
class pServiceBucket {
    
    /**
     * The hash map of services stored in the bucket.
     * @var pMap
     * @since 1.0-sofia
     */
    private $services;
    
    /**
     * Create a new pServiceBucket object
     * @since 1.0-sofia 
     */
    public function __construct() {
        $this->services = new pMap();
    }
    
    /**
     * Handles when the get{serviceName}Service() method is called in the class
     * @param string $name Name of the method
     * @param mixed $arguments Arguments. Ignored.
     * @since 1.0-sofia
     * @internal
     * @ignore
     */
    public function __call($name, $arguments) {
        if(substr($name, 0, 3) == 'get' && substr($name, -7) == 'Service'){
            $service = substr($name, 3, strlen($name) - 10);
            return $this->pick($service);
        }
    }
    
    /**
     * Put a service into the service bucket.
     * @param string $name The service identifier. Case-insensitive.
     * @param callback|Closure|object $resolver Either the object instance or
     *        the resolver function / method that will creates and return the
     *        object. Object returned must be an instance of the IService
     *        interface. The callback can accept the first parameter as the
     *        pServiceBucket that requested for the execution.
     * @since 1.0-sofia
     */
    public function put($name, $resolver){
        $name = strtolower($name);
        $this->services[$name] = $resolver;
    }
    
    /**
     * Pick out a service instance by the service identifier
     * @param string $serviceName The unique identifier of the service.
     *                            Case-insensitive.
     * @return object Returns the service instance, or NULL if not found.
     * @since 1.0-sofia 
     */
    public function pick($serviceName){
        $result = null;
        
        if(method_exists($this, 'get' . $serviceName . 'Service')){
            $result = $this->{'get' . $serviceName . 'Service'}();
            $this->put($serviceName, $result);
        }else{
            $serviceName = strtolower($serviceName);
            if($this->services->keyExists($serviceName)){
                $service = $this->services[$serviceName];
                if(is_callable($service)){
                    $service = call_user_func($service);
                    $this->services[$serviceName] = $service;
                }
                $result = $service;
            }
        }
        if($result instanceof IBucketUser){
            $result->bucket($this);
        }
        return $result;
    }
    
    /**
     * Check whether a particular service is available in the bucket
     * @param string $serviceName The name of the service to check for.
     * @return boolean Returns true if the service is available, false otherwise.
     * @since 1.0-sofia
     */
    public function contains($serviceName){
        return method_exists($this, 'get' . $serviceName . 'Service')
                || $this->services->keyExists(strtolower($serviceName));
    }
    
}