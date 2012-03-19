<?php

class pDbFactory {
    
    public static function create($config){
        pload('packfire.database.drivers.' . $config['driver'] . '.*');
        switch($config['driver']){
            case 'mysql':
                $connection  = new pMySqlConnection($config);
                $driver = new pMySqlDriver($connection);
                $schema = new pMySqlSchema($driver, $config['dbname']);
                return $schema;
                break;
        }
    }
    
}