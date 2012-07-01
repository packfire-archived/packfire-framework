<?php
pload('packfire.ioc.pServiceBucket');
pload('packfire.debugger.pDebugger');
pload('packfire.database.pDbConnectorFactory');
pload('packfire.session.pSessionLoader');
pload('packfire.config.framework.pAppConfig');
pload('packfire.config.framework.pRouterConfig');

/**
 * pAppServiceBucket class
 * 
 * Application service bucket that loads the application's core services as well
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
class pAppServiceBucket extends pServiceBucket {
    
    /**
     * Create a new pAppServiceBucket object
     * @since 1.0-sofia 
     */
    public function __construct() {
        parent::__construct();
        $this->load();
    }
    
    /**
     * Perform loading
     * @since 1.0-sofia 
     */
    protected function load(){
        $this->put('config.app', array('pAppConfig', 'load'));
        $this->put('config.routing', array('pRouterConfig', 'load'));
        $this->put('debugger', new pDebugger());
        $this->pick('debugger')->enabled($this->pick('config.app')->get('app', 'debug'));
        pServiceLoader::loadConfig($this);
        
        $databaseConfigs = $this->pick('config.app')->get('database');
        foreach($databaseConfigs as $key => $databaseConfig){
            $dbPackage = ($key == 'default' ? '' : '.' . $key);
            $this->put('database' . $dbPackage 
                    . '.driver', pDbConnectorFactory::create($databaseConfig));
            $this->put('database' . $dbPackage,
                    $this->pick('database' . $dbPackage . '.driver')->database());
        }
        if($this->pick('config.app')->get('session', 'enabled')){
            $sessionLoader = new pSessionLoader();
            $sessionLoader->setBucket($this);
            $sessionLoader->load();
        }
    }

}