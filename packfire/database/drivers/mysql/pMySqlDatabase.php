<?php

class pMySqlDatabase extends pDatabase {
    
    public function select($schema) {
        $this->driver->query('USE `%s`', $schema);
        return new pMySqlSchema($this->driver, $schema);
    }
    
}