<?php

/**
 * Provides functionalities to build models, entities and tables in the database.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.scaffold
 * @since 1.0-sofia
 */
class pScaffolder {
    
    /**
     * Name of the table or model to work on
     * @var string
     * @since 1.0-sofia
     */
    private $name;
    
    /**
     * 
     * @param string $name
     * @param pMap|array $parameters 
     * @return IView
     * @since 1.0-sofia
     */
    public function scaffold($name, $parameters){
        $this->name = $name;
        switch($parameters['action']){
            case 'build':
                break;
            case 'add':
                break;
            case 'view':
                break;
            case 'delete':
                break;
            case 'drop':
                break;
        }
    }
    
    protected function build(){
        
    }
    
    protected function add(){
        
    }
    
    protected function view(){
        
    }
    
    protected function delete(){
        
    }
    
    protected function drop(){
        
    }
    
    
}