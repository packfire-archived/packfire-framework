<?php
pload('packfire.database.connectors.pPdoConnector');
pload('pMySqlDatabase');

/**
 * pMySqlConnector class
 * 
 * Provides functionalities to and operations of a MySQL table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.drivers.mysql
 * @since 1.0-sofia
 */
class pMySqlConnector extends pPdoConnector {
    
    /**
     * Translates data type
     * @param string $type The input data type
     * @return string The translated data type
     * @since 1.0-sofia 
     */
    public function translateType($type) {
        $types = array(
            'pk' => 'int(11) NOT NULL auto_increment',
            'string' => 'varchar(255)',
            'integer' => 'int(11)',
            'timestamp' => 'datetime',
            'binary' => 'blob',
            'boolean' => 'tinyint(1)'
        );
        if(array_key_exists($type, $types)){
            return $types[$type];
        }
        return $type;
    }
    
    /**
     * Get the database representation
     * If there is no dbname defined in the config, the method returns a
     * pMySqlDatabase object. If a dbname is defined, the method will select
     * the schema and return a pMySqlSchema object.
     * @return pDatabase|pDbSchema Returns the database representation object
     * @since 1.0-sofia 
     */
    public function database(){
        $database = new pMySqlDatabase($this);
        if(array_key_exists('dbname', $this->config) && $this->config['dbname']){
            $database = $database->select($this->config['dbname']);
        }
        return $database;
    }
    
}