<?php

abstract class pDbConnection {
    
    public abstract function __construct($config);
    
    public abstract function connect();
    
}