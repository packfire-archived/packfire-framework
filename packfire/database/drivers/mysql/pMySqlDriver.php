<?php
pload('packfire.database.pDbDriver');

class pMySqlDriver extends pDbDriver {
    
    /**
     *
     * @var PDO
     */
    private $pdo;
    
    public function __construct($config){
        $username = $config['user'];
        $password = $config['password'];
        $dsn = sprintf('%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['dbname']);
        unset($config['host'], $config['driver'], $config['dbname'], $config['user'], $config['password']);
        $this->config = $config;
        $this->pdo = new PDO($dsn, $username, $password, $config);
    }
    
    public function pdo(){
        return $this->pdo;
    }
    
    public function processDataType($value){
        switch(gettype($value)){
            case 'integer':
                $value = (int)$value;
                break;
            case 'string':
                $value = '\'' . $this->escape($value) . '\'';
                break;
            case 'float':
            case 'double':
                $value = (double)$value;
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
    
    public function escape($string){
        return mysql_real_escape_string($string);
    }
    
}