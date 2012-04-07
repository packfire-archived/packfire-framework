<?php
pload('packfire.database.pDbConnector');

/**
 * Provides functionalities to and operations of a MySQL table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.drivers.mysql
 * @since 1.0-sofia
 */
class pMySqlConnector extends pDbConnector {
    
    public function processDataType($value){
        switch(gettype($value)){
            case 'integer':
                $value = (int)$value;
                break;
            case 'string':
                $value = '\'' . mysql_real_escape_string($value) . '\'';
                break;
            case 'float':
            case 'double':
                $value = (double)$value;
                break;
            case 'object':
                if($value instanceof pDbExpression){
                    $value = $value->expression();
                }elseif($value instanceof pDbCommand){
                    $value = $value->query();
                }
                break;
        }
        return $value;
    }
    
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
    
    public function database(){
        $database = new pMySqlDatabase($this);
        if($this->config['dbname']){
            $database = $database->select($this->config['dbname']);
        }
        return $database;
    }
    
}