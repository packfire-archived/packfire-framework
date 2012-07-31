<?php
pload('packfire.ioc.pBucketLoader');
pload('packfire.debugger.pDebugger');
pload('packfire.database.pDbConnectorFactory');
pload('packfire.session.pSessionLoader');
pload('packfire.config.framework.pAppConfig');
pload('packfire.ioc.pServiceLoader');

/**
 * pServiceAppLoader class
 * 
 * Application service bucket that loads the application's core services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
class pServiceAppLoader extends pBucketLoader {
    
    /**
     * Perform loading
     * @since 1.0-sofia 
     */
    public function load(){
        $this->put('config.app', array('pAppConfig', 'load'));
        pServiceLoader::loadConfig($this);
        if($this->pick('config.app')){
            // load database drivers and configurations
            $databaseConfigs = $this->pick('config.app')->get('database');
            if($databaseConfigs){
                foreach($databaseConfigs as $key => $databaseConfig){
                    $dbPackage = ($key == 'default' ? '' : '.' . $key);
                    $this->put('database' . $dbPackage 
                            . '.driver', pDbConnectorFactory::create($databaseConfig));
                    $this->put('database' . $dbPackage,
                            array($this->pick('database' . $dbPackage . '.driver'), 'database'));
                }
            }
        }
    }

}