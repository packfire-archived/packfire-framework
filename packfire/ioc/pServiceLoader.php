<?php
pload('IServiceLoader');
pload('packfire.config.framework.pIoCConfig');

/**
 * The service loader
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.ioc
 * @since 1.0-sofia
 */
class pServiceLoader implements IServiceLoader {
    
    /**
     * The full package
     * @var string
     * @since 1.0-sofia
     */
    private $package;
    
    /**
     * The parameters 
     * @var array|pList
     * @since 1.0-sofia
     */
    private $params;
    
    /**
     * Create a new pServiceLoader object
     * @param string $package The full package quantifier
     * @param array|pList $params Parameters to the constructor
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
        
        list($package, $class) = pClassLoader::resolvePackageClass($this->package);
        
        if(!class_exists($class)){
            pload($package);
        }
        
        if(class_exists($class)){
            $reflect  = new ReflectionClass($class);
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
        $servicesConfig = pIoCConfig::load();
        $services = $servicesConfig->get();
        foreach($services as $key => $service){
            $service = new pMap($service);
            if($service->keyExists('class')){
                $loader = new self($service->get('class'),
                        $service->get('parameters'));
                $bucket->put($key, array($loader, 'load'));
            }else{
                // todo throw exception no class defined
            }
        }
    }
    
}