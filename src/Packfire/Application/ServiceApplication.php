<?php
namespace Packfire\Application;

use Packfire\Application\IApplication;
use Packfire\Application\ServiceAppLoader;
use Packfire\IoC\BucketUser;
use Packfire\IoC\ServiceBucket;

/**
 * ServiceApplication class
 * 
 * A generic application that uses 
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application
 * @since 1.0-elenor
 */
abstract class ServiceApplication extends BucketUser implements IApplication {
    
    /**
     * Create a new ServiceApplication object
     * @since 1.0-elenor
     */
    public function __construct(){
        $this->services = new ServiceBucket();
        
        $coreLoader = new ServiceAppLoader($this->services);
        $coreLoader->load();
    }
    
}