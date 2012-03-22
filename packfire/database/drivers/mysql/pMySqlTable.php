<?php
pload('packfire.database.pDbTable');
pload('packfire.database.pDbColumn');

class pMySqlTable extends pDbTable {
    
    /**
     *
     * @var pMySqlConnector
     */
    protected $driver;
    
    /**
     *
     * @var pList
     */
    protected $columns;
    
    /**
     *
     * @var pList
     */
    protected $primaryKeys;
    
    /**
     *
     * @param pDbColumn $column 
     */
    public function add($column) {
        $this->driver->query('ALTER TABLE `%s` ADD COLUMN `%s` %s', $this->name,
                $column->name(), $this->driver->translateType($column->type()));
        $this->columns();
        $this->columns->add($column);
    }

    public function remove($column) {
        if($column instanceof pDbColumn){
            $column = $column->name();
        }
        $this->driver->query('ALTER TABLE `%s` DROP `%s`', $this->name, $column);
        $this->columns();
        foreach($this->columns as $col){
            if($col->name() == $column){
                $this->columns->remove($col);
                break;
            }
        }
    }
    
    public function get($row){
        $query = 'SELECT * FROM `%s` WHERE ';
        $pks = array();
        foreach($this->pk() as $column){
            $pks[] = '`'.$column->name().'` = ' . $this->driver->processDataType($row[$column->name()]);
        }
        $query .= implode(' AND ', $pks);
        $statement = $this->driver->query($query, $this->name);
        return $statement->fetchAll();
    }
    
    public function delete($row) {
        $query = 'DELETE FROM `%s` WHERE ';
        $where = array();
        foreach($this->pk() as $column){
            $where[] = '`'.$column->name().'` = ' . $this->driver->processDataType($row[$column->name()]);
        }
        $query .= implode(' AND ', $where);
        $this->driver->query($query, $this->name);
    }

    public function insert($row) {
        $query = 'INSERT INTO `%s` (';
        $columns = array();
        $values = array();
        foreach($this->columns as $column){
            if(array_key_exists($column->name(), $row)){
                $columns[] = '`' . $column->name() . '`';
                $values[] = $this->driver->processDataType($row[$column->name()]);
            }
        }
        $query .= implode(', ', $columns) . ') VALUES (';
        $query .= implode(', ', $values) . ')';
        $this->driver->query($query, $this->name);
    }

    public function update($row) {
        $columns = $this->columns();
        $query = 'UPDATE `%s` SET ';
        $data = array();
        $pks = array();
        foreach($columns as $column){
            if($column->type() == 'pk'){
                $pks[] = '`'.$column->name().'` = ' . $this->driver->processDataType($row[$column->name()]);
            }else{
                $data[] = '`'.$column->name().'` = ' . $this->driver->processDataType($row[$column->name()]);
            }
        }
        $query .= implode(', ', $data) . ' WHERE ';
        $query .= implode(' AND ', $pks);
        $this->driver->query($query, $this->name);
    }
    
    public function columns(){
        if(!$this->columns){
            $statement = $this->driver->query('SHOW COLUMNS FROM `%s`', $this->name);
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
    
    public function pk(){
        if(!$this->primaryKeys){
            $statement = $this->driver->query('SHOW COLUMNS FROM `%s` WHERE `Key` =  \'PRI\'', $this->name);
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