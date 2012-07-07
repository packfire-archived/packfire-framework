<?php

/**
 * tMockEventButton class
 * 
 * mimics a button that allows the handling of different events
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.0-elenor
 */
class tMockEventButton {
    
    /**
     *
     * @var pEventHandler
     * @since 1.0-elenor
     */
    private $eventHandler;
    
    public function __construct(){
        $this->eventHandler = new pEventHandler();
    }
    
    public function click($handler = null){
        if(func_num_args() == 1){
            $this->eventHandler->on(__METHOD__, $handler);
        }else{
            $this->eventHandler->trigger(__METHOD__, array('x' => 100, 'y' => 120));
        }
    }
    
    public function focus($handler = null){
        if(func_num_args() == 1){
            $this->eventHandler->on(__METHOD__, $handler);
        }else{
            $this->eventHandler->trigger(__METHOD__);
        }
    }
    
    public function blur($handler = null){
        if(func_num_args() == 1){
            $this->eventHandler->on(__METHOD__, $handler);
        }else{
            $this->eventHandler->trigger(__METHOD__);
        }
    }
    
}