<?php
pload('IApplication');
pload('packfire.ioc.pBucketUser');
pload('pServiceAppLoader');
pload('packfire.ioc.pServiceBucket');

/**
 * pServiceApplication abstract class
 * 
 * A generic application that uses 
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-elenor
 */
abstract class pServiceApplication extends pBucketUser implements IApplication {
    
    /**
     * Create a new pServiceApplication object
     * @since 1.0-elenor
     */
    public function __construct(){
        $this->services = new pServiceBucket();
        
        $coreLoader = new pServiceAppLoader($this->services);
        $coreLoader->load();
        $this->services->put('events', new pEventHandler($this));
    }
    
}