<?php
pload('packfire.database.IDbConnector');
pload('packfire.ioc.pBucketUser');

/**
 * A connector that helps to connect to the database
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
abstract class pPdoConnector extends pBucketUser implements IDbConnector {
    
    /**
     * The PDO object
     * @var PDO
     * @since 1.0-sofia
     */
    protected $pdo;
    
    /**
     * The array of configuration
     * @var array|pMap
     * @since 1.0-sofia
     */
    protected $config;
    
    /**
     * Create a new pDbConnector object
     * @param array|pMap $config An array of configuration
     * @since 1.0-sofia
     */
    public function __construct($config){
        $this->config = $config;
        $username = $config['user'];
        $password = $config['password'];
        $dsn = sprintf('%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['dbname']);
        unset($config['host'], $config['driver'], $config['dbname'], $config['user'], $config['password']);
        $this->pdo = new PDO($dsn, $username, $password, $config);
    }
    
    /**
     * Prepare and bind a statement for execution
     * @param string $query The query to be prepared
     * @param array|pMap $params (optional) The parameters of the query
     * @returns PDOStatement Returns the PDOStatement ready to be executed.
     * @since 1.0-sofia
     */
    public function binder($query, $params = array()){
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
    
    /**
     * Translates data type
     * @param string $type The input data type
     * @return string The translated data type
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
        $this->service('debugger')->query($query, 'prepare');
        return $this->pdo->prepare($query);
    }
    
    /**
     * Create and execute a PDOStatement
     * @param string $query The statement to execute
     * @return PDOStatement Returns the PDOStatement object executed.
     * @since 1.0-sofia
     */
    public function query($query){
        $this->service('debugger')->query($query);
        return $this->pdo->query($query);
    }
    
    /**
     * Get the last insert ID
     * @return mixed
     * @since 1.0-sofia
     */
    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Begin the transaction
     * @return boolean Returns true if the transaction has been created, false otherwise.
     * @since 1.0-sofia 
     */
    public function begin(){
        return $this->pdo->beginTransaction();
    }
    
    /**
     * End and commit the transaction
     * @since 1.0-sofia 
     */
    public function commit(){
        $this->pdo->commit();
    }
    
    /**
     * End and revert changes made by the transaction
     * @since 1.0-sofia 
     */
    public function rollback(){
        $this->pdo->rollBack();
    }
    
}