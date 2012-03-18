<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pMySqlTable
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 */
class pMySqlTable extends pDbTable {
    
    public function add($column) {
        
    }

    public function remove($column) {
        if($column instanceof pDbColumn){
            $column = $column->name();
        }
        $this->driver->query('ALTER TABLE `%s` DROP `%s`', $this->name, $column);
    }

    public function create($columns) {
        
    }

    public function delete($row) {
        
    }

    public function insert($row) {
        
    }

    public function update($row) {
        
    }
    
}