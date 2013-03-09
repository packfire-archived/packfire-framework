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

use Packfire\Collection\Map;
use Packfire\Exception\ServiceException;
use Packfire\Config\Framework\IoCConfig;

/**
 * The service loader for ioc.yml file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\IoC
 * @since 1.0-sofia
 */
class ServiceLoader {
    
    /**
     * The full package
     * @var string
     * @since 1.0-sofia
     */
    private $package;
    
    /**
     * The parameters 
     * @var array|ArrayList
     * @since 1.0-sofia
     */
    private $params;
    
    /**
     * Create a new ServiceLoader object
     * @param string $package The full package quantifier
     * @param array|ArrayList $params Parameters to the constructor
     * @since 1.0-sofia
     */
    public function __construct($package, $params = null){
        $this->package = $package;
        $this->params = $params;
    }
    
    /**
     * Load the service
     * @return object Returns the instance loaded
     * @since 1.0-sofia
     */
    public function load(){
        $instance = null;
        $params = $this->params;
        if(!$params){
            $params = array();
        }
                
        if(class_exists($this->package)){
            $reflect  = new \ReflectionClass($this->package);
            if($params){
                $instance = $reflect->newInstanceArgs($params);
            }else{
                $instance = $reflect->newInstance();
            }
        }
        return $instance;
    }
    
    /**
     * Load services from the configuration file 
     * @param IServiceBucket $bucket The service bucket to load into
     * @since 1.0-sofia
     */
    public static function loadConfig($bucket){
        $iocConfig = new IoCConfig();
        $servicesConfig = $iocConfig->load();
        if($servicesConfig){
            $services = $servicesConfig->get();
            foreach($services as $key => $service){
                $service = new Map($service);
                if($service->keyExists('class')){
                    $loader = new self($service->get('class'),
                            $service->get('parameters'));
                    $bucket->put($key, array($loader, 'load'));
                }else{
                    throw new ServiceException('Service "' . $key
                            . '" defined in the service configuration'
                            . ' file "ioc.yml" contains not conain a'
                            . ' class definition.');
                }
            }
        }
    }
    
}