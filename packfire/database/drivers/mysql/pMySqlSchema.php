<?php

class pMySqlSchema extends pDbSchema {
    
    public function add($table) {
        $query = 'CREATE TABLE IF NOT EXISTS `%s` (';
        $columns = array();
        $primaryKeys = array();
        foreach($table->columns() as $column){
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
        $this->driver->query($query, $this->name)->execute();
    }

    public function create() {
        $this->driver->query('CREATE SCHEMA `' . $this->name . '`')->execute();
    }

    public function delete() {
        $this->driver->query('DROP SCHEMA `' . $this->name . '`')->execute();
    }

    public function remove($table) {
        if($table instanceof pDbTable){
            $table = $table->name();
        }
        $this->driver->query('DROP `' . $this->name . '`.`' . $table . '`')->execute();
    }

    public function table($table) {
        return new pMySqlTable($this->driver, $table);
    }
    
}