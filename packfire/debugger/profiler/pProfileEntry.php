<?php

/**
 * An entry in the profiling entries
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger.profiler
 * @since 1.0-sofia
 */
class pProfileEntry {
    
    private $time;
    
    private $file;
    
    private $call;
    
    private $memory;
    
    private $caller;
    
    private $execTime;
    
    public function __construct($time, $call, $memory, $execTime, $file = null, $caller = null){
        $this->time = $time;
        $this->call = $call;
        $this->memory = $memory;
        $this->execTime = $execTime;
        $this->caller = $caller;
        $this->file = $file;
    }
    
    public function time(){
        return $this->time;
    }
    
    public function file(){
        return $this->file;
    }
    
    public function call(){
        return $this->call;
    }
    
    public function execTime(){
        return $this->execTime;
    }
    
    public function memory(){
        return $this->memory;
    }
    
    public function caller(){
        return $this->caller;
    }
    
}