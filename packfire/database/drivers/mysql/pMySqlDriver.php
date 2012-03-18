<?php

class pMySqlDriver extends pDbDriver {
    
    /**
     *
     * @var PDO
     */
    private $pdo;
    
    public function __construct($connection){
        parent::__construct($connection);
        $this->pdo = $this->connection->connect();
    }
    
    public function query($query){
        $query = call_user_func_array('sprintf', func_get_args());
        return $this->pdo->query($query);
    }
    
    public function translateType($type) {
        
    }
    
}