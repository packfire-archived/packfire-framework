<?php
pload('packfire.collection.pMap');

/**
 * Abstraction of a database table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database
 * @since 1.0-sofia
 */
abstract class pDbTable {
    
    /**
     * The database connector
     * @var pDbConnector
     * @since 1.0-sofia
     */
    protected $driver;
    
    /**
     * The name of this table
     * @var string
     * @since 1.0-sofia
     */
    protected $name;
    
    /**
     * Create a new pDbTable object
     * @param pDbConnector $driver The connector
     * @param string $name The name of the table 
     * @since 1.0-sofia
     */
    public function __construct($driver, $name){
        $this->driver = $driver;
        $this->name = $name;
    }
    
    /**
     * Get the name of the table
     * @return string
     */
    public function name(){
        return $this->name;
    }
    
    public abstract function add($column);
    
    public abstract function remove($column);
    
    public abstract function insert($row);
    
    public abstract function get($row);
    
    public abstract function delete($row);
    
    public abstract function update($row);
    
    public abstract function columns();
    
    public abstract function pk();
    
}