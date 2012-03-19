<?php
pload('packfire.database.pDbConnection');

class pMySqlConnection extends pDbConnection {
    
    private $config;
    
    private $pdo;
    
    public function __construct($config){
        $username = $config['user'];
        $password = $config['password'];
        $dsn = sprintf('%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['dbname']);
        unset($config['host'], $config['driver'], $config['dbname'], $config['user'], $config['password']);
        $this->config = $config;
        $this->pdo = new PDO($dsn, $username, $password, $config);
    }
    
    public function connect(){
        return $this->pdo;
    }
    
}