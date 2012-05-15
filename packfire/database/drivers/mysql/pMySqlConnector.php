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
    
    /**
     *
     * @param string $query
     * @param array|pMap $params 
     * @returns PDOStatement
     * @since 1.0-sofia
     */
    public function binder($query, $params){
        $values = array();
        foreach($params as $name => $value){
            if($value instanceof pDbExpression){
                if(substr($name, 0, 1) != ':'){
                    $name = ':' . $name;
                }
                $query = str_replace($name, $value->expression(), $query);
            }else{
                $values[$name] = $value;
            }
        }
        $statement = $this->prepare($query);
        foreach($values as $name => $value){
            $statement->bindValue($name, $value);
        }
        return $statement;
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