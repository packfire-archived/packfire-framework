<?php

/**
 * Database Factory
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
class pDbFactory {
    
    /**
     * Create the database or schema object based on the configuration provided.
     * @param array|pMap $config The database configuration
     * @return pDbSchema|pDbDatabase Returns the schema or database
     * @since 1.0-sofia
     */
    public static function create($config){
        pload('packfire.database.drivers.' . $config['driver'] . '.*');
        switch($config['driver']){
            case 'mysql':
                $db = new pMySqlDatabase(new pMySqlConnector($config));
                $schema = $db->select($config['dbname']);
                return $schema;
                break;
        }
    }
    
}
