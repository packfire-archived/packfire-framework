<?php
pload('packfire.database.pDbSchema');
pload('packfire.database.pDbTable');

class pMySqlSchema extends pDbSchema {
    
    public function add($name, $columns) {
        $query = 'CREATE TABLE IF NOT EXISTS `%s` (';
        $columns = array();
        $primaryKeys = array();
        foreach($columns as $column){
            $columns[] = '`'. $column->name() . '` ' . $this->driver->translateType($column->type());
            if($column->type() == 'pk'){
                $primaryKeys[] = 'PRIMARY KEY (`'. $column. '`)';
            }
        }
        $query .= implode(', ', $columns);
        if(count($primaryKeys) > 0){
            $query .= ', ' . implode(', ', $primaryKeys);
        }
        $query .= ')';
        $this->driver->query($query, $name);
        return $this->table($name);
    }

    public function create() {
        $this->driver->query('CREATE SCHEMA `' . $this->name . '`');
    }

    public function delete() {
        $this->driver->query('DROP SCHEMA `' . $this->name . '`');
    }

    public function remove($table) {
        if($table instanceof pDbTable){
            $table = $table->name();
        }
        $this->driver->query('DROP `' . $this->name . '`.`' . $table . '`');
    }
    
    public function truncate($table){
        if($table instanceof pDbTable){
            $table = $table->name();
        }
        $this->driver->query('TRUNCATE TABLE `%s`', $table);
    }

    public function table($table) {
        $table = new pMySqlTable($this->driver, $table);
        $table->columns();
        return $table;
    }
    
}