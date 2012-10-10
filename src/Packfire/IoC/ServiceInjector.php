<?php
namespace Packfire\IoC;

use BucketUser;
use ServiceBucket;

/**
 * ServiceInjector class
 * 
 * An injector that helps to inject dependencies into an IoC bucket
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IoC
 * @since 1.1-sofia
 */
class ServiceInjector extends BucketUser {
    
    /**
     * Create a new ServiceInjector object
     * @param BucketUser|ServiceBucket $object The object to be injected
     * @since 1.1-sofia
     */
    public function __construct($object){
        if($object instanceof BucketUser){
            $this->copyBucket($object);
        }elseif($object instanceof ServiceBucket){
            $this->setBucket($object);
        }
    }
    
    /**
     * Inject a service into the service bucket
     * @param string $name Name of the service to inject
     * @param mixed $service The service to inject
     * @since 1.1-sofia
     */
    public function inject($name, $service){
        $this->services->put($name, $service);
    }
    
}