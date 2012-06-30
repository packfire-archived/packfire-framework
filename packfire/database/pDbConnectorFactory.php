<?php

/**
 * pDbConnectorFactory
 * 
 * Creates database connector
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
class pDbConnectorFactory {
    
    /**
     * Create the database connector based on the configuration provided.
     * @param array|pMap $config The database configuration
     * @return pDbConnector Returns the database connector
     * @since 1.0-sofia
     */
    public static function create($config){
        pload('packfire.database.drivers.' . $config['driver'] . '.*');
        switch($config['driver']){
            case 'mysql':
                return new pMySqlConnector($config);
                break;
        }
    }
    
}
