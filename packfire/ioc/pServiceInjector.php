<?php
pload('pBucketUser');
pload('pServiceBucket');

/**
 * pServiceInjector class
 * 
 * An injector that helps to inject dependencies into an IoC bucket
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.ioc
 * @since 1.1-sofia
 */
class pServiceInjector extends pBucketUser {
    
    public function __construct($object){
        if($object instanceof pBucketUser){
            $this->copyBucket($object);
        }elseif($object instanceof pServiceBucket){
            $this->setBucket($object);
        }
    }
    
    public function inject($name, $service){
        $this->services->put($name, $service);
    }
    
}