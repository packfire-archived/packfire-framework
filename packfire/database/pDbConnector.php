<?php

/**
 * A connector that helps to connect to the database
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
abstract class pDbConnector {
    
    /**
     * The PDO object
     * @var PDO
     * @since 1.0-sofia
     */
    protected $pdo;
    
    /**
     * Create the connector based on the configuration provided.
     * @param array $config An array of configuration
     * @since 1.0-sofia
     */
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
     * Create a PDOStatement and prepare it for execution 
     * @param string $query The statement
     * @return PDOStatement Returns the PDOStatement object
     * @since 1.0-sofia
     */
    public function prepare($query){
        return $this->pdo->prepare($query);
    }
    
    /**
     * Create and execute a PDOStatement
     * @param string $query The statement to execute
     * @return PDOStatement Returns the PDOStatement object executed.
     * @since 1.0-sofia
     */
    public function query($query){
        return $this->pdo->query($query);
    }
    
}