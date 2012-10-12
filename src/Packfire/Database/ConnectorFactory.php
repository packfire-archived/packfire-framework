<?php
namespace Packfire\Database;

use Packfire\Database\Drivers\MySql\Connector as MySqlConnector;

/**
 * ConnectorFactory class
 *
 * Creates database connector
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Database
 * @since 1.0-sofia
 */
class ConnectorFactory {

    /**
     * Create the database connector based on the configuration provided.
     * @param array|Map $config The database configuration
     * @return Connector Returns the database connector
     * @since 1.0-sofia
     */
    public static function create($config){
        switch($config['driver']){
            case 'mysql':
                return new MySqlConnector($config);
                break;
        }
    }

}
