<?php

class pDbDriverFactory {
    
    public static function create($config){
        pload('packfire.database.drivers.' . $config['driver'] . '.*');
        switch($config['driver']){
            case 'mysql':
                $driver = new pMySqlDriver($config);
                return $driver;
                break;
        }
    }
    
}
