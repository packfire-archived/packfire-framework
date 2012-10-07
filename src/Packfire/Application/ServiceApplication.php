<?php
namespace Packfire\Application;

use IApplication;
use ServiceAppLoader;
pload('packfire.ioc.pBucketUser');
pload('packfire.ioc.pServiceBucket');
pload('packfire.event.pEventHandler');

/**
 * pServiceApplication abstract class
 * 
 * A generic application that uses 
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application
 * @since 1.0-elenor
 */
abstract class ServiceApplication extends pBucketUser implements IApplication {
    
    /**
     * Create a new ServiceApplication object
     * @since 1.0-elenor
     */
    public function __construct(){
        $this->services = new pServiceBucket();
        
        $coreLoader = new ServiceAppLoader($this->services);
        $coreLoader->load();
        $this->services->put('events', new pEventHandler($this));
    }
    
}