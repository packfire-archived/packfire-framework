<?php

class pDbConnectorFactory {
    
    public static function create($config){
        pload('packfire.database.drivers.' . $config['driver'] . '.*');
        switch($config['driver']){
            case 'mysql':
                $driver = new pMySqlConnector($config);
                return $driver;
                break;
        }
    }
    
}
