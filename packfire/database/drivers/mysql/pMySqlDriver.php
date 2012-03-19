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
    
    /**
     *
     * @param type $query
     * @return PDOStatement
     */
    public function query($query){
        $query = call_user_func_array('sprintf', func_get_args());
        return $this->pdo->query($query);
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
    
}