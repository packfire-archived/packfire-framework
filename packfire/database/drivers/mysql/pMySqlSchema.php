<?php

class pMySqlSchema extends pDbSchema {
    
    public function add($table) {
        
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

    public function table($table) {
        return new pMySqlTable($this->driver, $table);
    }
    
}