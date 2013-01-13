<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Database;

use Packfire\Database\Drivers\MySql\Connector as MySqlConnector;

/**
 * Creates database connector based on configuration
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
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
