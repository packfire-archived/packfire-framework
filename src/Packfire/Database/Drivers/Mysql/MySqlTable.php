<?php
pload('packfire.database.pDbTable');
pload('packfire.database.pDbColumn');
pload('packfire.database.drivers.mysql.linq.pMySqlLinq');

/**
 * pMySqlTable class
 * 
 * Provides functionalities to and operations of a MySQL table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.drivers.mysql
 * @since 1.0-sofia
 */
class pMySqlTable extends pDbTable {
    
    /**
     * The connection driver
     * @var pMySqlConnector
     * @since 1.0-sofia
     */
    protected $driver;
    
    /**
     * The list of columns in the table
     * @var pList
     * @since 1.0-sofia
     */
    protected $columns;
    
    /**
     * The list of primary keys in the table
     * @var pList
     * @since 1.0-sofia
     */
    protected $primaryKeys;
    
    /**
     * Add a column to the table
     * @param pDbColumn $column The new column to add to the table
     * @since 1.0-sofia
     */
    public function add($column) {
        $this->driver->query(sprintf('ALTER TABLE `%s` ADD COLUMN `%s` %s', $this->name,
                $column->name(), $this->driver->translateType($column->type())));
        $this->columns();
        $this->columns->add($column);
    }

    /**
     * Remove a column from the table
     * @param string|pDbColumn $column The column or the name of the column to
     *              remove from the table.
     * @since 1.0-sofia
     */
    public function remove($column) {
        if($column instanceof pDbColumn){
            $column = $column->name();
        }
        $this->driver->query(sprintf('ALTER TABLE `%s` DROP `%s`', $this->name, $column));
        $this->columns();
        foreach($this->columns as $item){
            if($item->name() == $column){
                $this->columns->remove($item);
                break;
            }
        }
    }
    
    /**
     * Get a row from the table by its primary keys
     * @param array|pMap $row The row's primary keys
     * @return array Returns the row data
     * @since 1.0-sofia
     */
    public function get($row){
        $linq = new pMySqlLinq($this->driver, $this->name);
        $pks = array();
        $params = array();
        foreach($this->pk() as $column){
            if(array_key_exists($column->name(), $row)){
                $pks[] = '`' . $column->name() . '` = :' . $column->name();
                $params[$column->name()] = $row[$column->name()];
            }
        }
        $query .= implode(' AND ', $pks);
        return $linq->where($query)->params($params)->fetch()->first();
    }
    
    /**
     * Delete rows from the table
     * @param array|pMap $row (optional) The conditions to delete the rows. If
     *          this is not specified, all rows from the table will be deleted.
     * @since 1.0-sofia
     */
    public function delete($row = null) {
        $query = 'DELETE FROM `%s`';
        if($row){
            $query .= ' WHERE ';
            $where = array();
            $params = array();
            foreach($this->columns() as $column){
                if(array_key_exists($column->name(), $row)){
                    $where[] = '`' . $column->name() . '` = :' . $column->name();
                    $params[$column->name()] = $row[$column->name()];
                }
            }
            $query .= implode(' AND ', $where);
            $statement = $this->driver->binder(sprintf($query, $this->name), $params);
        }else{
            $statement = $this->driver->prepare(sprintf($query, $this->name));
        }
        $statement->execute();
    }

    /**
     * Insert a row into the table
     * @param array|pMap $row The row to insert into the table
     * @since 1.0-sofia
     */
    public function insert($row) {
        $query = 'INSERT INTO `%s` (';
        $columns = array();
        $values = array();
        $params = array();
        foreach($this->columns() as $column){
            if(array_key_exists($column->name(), $row)){
                $columns[] = '`' . $column->name() . '`';
                $params[$column->name()] = $row[$column->name()];
                $values[] = ':' . $column->name();
            }
        }
        $query .= implode(', ', $columns) . ') VALUES (';
        $query .= implode(', ', $values) . ')';
        $statement = $this->driver->binder(sprintf($query, $this->name), $params);
        $statement->execute();
    }

    /**
     * Update a row in the table
     * @param array|pMap $row The updated information. Primary key should be
     *           included here if $where is not set.
     * @param array|pMap $where (optional) The conditions to update the rows
     * @since 1.0-sofia
     */
    public function update($row, $where = null) {
        $query = 'UPDATE `%s` SET ';
        $data = array();
        $pks = array();
        $params = array();
        foreach($this->columns() as $column){
            if(array_key_exists($column->name(), $row)){
                $params[$column->name()] = $row[$column->name()];
                if($column->type() == 'pk' && !$where){
                    $pks[] = '`'.$column->name().'` = :' . $column->name();
                }elseif($column->type() != 'pk'){
                    $data[] = '`'.$column->name().'` = :' . $column->name();
                }
            }
        }
        $query .= implode(', ', $data) . ' WHERE ';
        if($where){
            foreach($this->columns() as $column){
                if(array_key_exists($column->name(), $where)){
                    $params[$column->name()] = $where[$column->name()];
                    $pks[] = '`' . $column->name() . '` = :' . $column->name();
                }
            }
        }
        $query .= implode(' AND ', $pks);
        $statement = $this->driver->binder(sprintf($query, $this->name), $params);
        $statement->execute();
    }
    
    /**
     * Get the columns of the table
     * @return pList Returns a list of pDbColumn objects
     * @since 1.0-sofia
     */
    public function columns(){
        if(!$this->columns){
            $statement = $this->driver->query(sprintf('SHOW COLUMNS FROM `%s`', $this->name));
            if($statement){
                $cols = $statement->fetchAll();
                $columns = new pList();
                foreach($cols as $col){
                    $type = array();
                    if($col[3] == 'PRI'){
                        $type[] = 'pk';
                    }else{
                        if($col[1]){
                            $type[] = $col[1];
                        }
                        if($col[2] == 'NO'){
                            $type[] = 'NOT NULL';
                        }
                        if($col[4]){
                            $type[] = $col[4];
                        }
                    }
                    $type = implode(' ', $type);
                    $column = new pDbColumn($col[0], $type);
                    $columns->add($column);
                }
                $this->columns = $columns;
            }
        }
        return $this->columns;
    }
    
    /**
     * Get the list of primary keys of the table
     * @return pList Returns a list of pDbColumn objects of the primary keys
     * @since 1.0-sofia
     */
    public function pk(){
        if(!$this->primaryKeys){
            $statement = $this->driver->query(sprintf('SHOW COLUMNS FROM `%s` WHERE `Key` =  \'PRI\'', $this->name));
            $cols = $statement->fetchAll();
            $columns = new pList();
            foreach($cols as $col){
                $columns[] = new pDbColumn($col[0], 'pk');
            }
            $this->primaryKeys = $columns;
        }
        return $this->primaryKeys;
    }
    
}