<?php
namespace Packfire\Application;

use Packfire\IoC\BucketLoader;
use Packfire\Database\ConnectorFactory;
use Packfire\Config\Framework\AppConfig;
use Packfire\IoC\ServiceLoader;
use Packfire\Event\EventHandler;

/**
 * ServiceAppLoader class
 * 
 * Application service bucket that loads the application's core services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application
 * @since 1.0-sofia
 */
class ServiceAppLoader extends BucketLoader {
    
    /**
     * Perform loading
     * @since 1.0-sofia 
     */
    public function load(){
        $this->put('config.app', array(new AppConfig(), 'load'));
        if($this->pick('config.app')){
            // load database drivers and configurations
            $databaseConfigs = $this->pick('config.app')->get('database');
            if($databaseConfigs){
                foreach($databaseConfigs as $key => $databaseConfig){
                    $dbPackage = ($key == 'default' ? '' : '.' . $key);
                    $this->put('database' . $dbPackage 
                            . '.driver', ConnectorFactory::create($databaseConfig));
                    $this->put('database' . $dbPackage,
                            array($this->pick('database' . $dbPackage . '.driver'), 'database'));
                }
            }
        }
        $this->put('events', new EventHandler($this));
        $this->put('shutdown', 'Packfire\Core\ShutdownTaskManager');
        ServiceLoader::loadConfig($this);
    }

}