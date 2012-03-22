<?php
pload('packfire.database.pDbDriver');

class pMySqlConnector extends pDbConnector {
    
    /**
     *
     * @var PDO
     */
    private $pdo;
    
    public function processDataType($value){
        switch(gettype($value)){
            case 'integer':
                $value = (int)$value;
                break;
            case 'string':
                $value = '\'' . mysql_real_escape_string($value) . '\'';
                break;
            case 'float':
            case 'double':
                $value = (double)$value;
                break;
            case 'object':
                if($value instanceof pDbExpression){
                    $value = $value->expression();
                }elseif($value instanceof pDbCommand){
                    $value = $value->query();
                }
                break;
        }
        return $value;
    }
    
    public function translateType($type) {
        $types = array(
            'pk' => 'int(11) NOT NULL auto_increment',
            'string' => 'varchar(255)',
            'integer' => 'int(11)',
            'timestamp' => 'datetime',
            'binary' => 'blob',
            'boolean' => 'tinyint(1)'
        );
        if(array_key_exists($type, $types)){
            return $types[$type];
        }
        return $type;
    }
    
}