<?php

abstract class pDbConnector {
    
    /**
     *
     * @var PDO
     */
    protected $pdo;
    
    public function __construct($config){
        $username = $config['user'];
        $password = $config['password'];
        $dsn = sprintf('%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['dbname']);
        unset($config['host'], $config['driver'], $config['dbname'], $config['user'], $config['password']);
        $this->pdo = new PDO($dsn, $username, $password, $config);
    }
    
    /**
     * Translates data type
     * @param string $type The input data type
     * @since 1.0-sofia 
     */
    public abstract function translateType($type);
    
    /**
     *
     * @param string $query
     * @return PDOStatement
     */
    public function prepare($query){
        return $this->pdo->prepare($query);
    }
    
    /**
     *
     * @param string $query
     * @return PDOStatement
     */
    public function query($query){
        return $this->pdo->query($query);
    }
    
}