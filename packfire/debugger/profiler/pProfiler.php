<?php
pload('packfire.datetime.pDateTime');
pload('pProfileEntry');
pload('packfire.collection.pList');

/**
 * pProfiler Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.debugger
 * @since 1.0-sofia
 */
class pProfiler {
    
    /**
     * The list of entries
     * @var pList
     * @since 1.0-sofia
     */
    private $entries;
    
    public function __construct(){
        $this->entries = new pList();
    }
    
    public function entries(){
        return $this->entries;
    }
    
    public function start(){
        register_tick_function(array(&$this, 'ticker'));
    }
    
    public function stop(){
        unregister_tick_function(array(&$this, 'ticker'));
    }
    
    public function ticker(){
        $trace = debug_backtrace();
        static $time = 0;
        $call = null;
        $file = null;
        if(array_key_exists(1, $trace)){
            $call = $trace[1]["function"] . '()';
            if(array_key_exists('file', $trace[1])){
                $file = $trace[1]["file"] . ': ' . $trace[1]["line"];
            }
        }
        $caller = null;
        if(array_key_exists(2, $trace)){
            $caller = $trace[2]["function"].'()';
            if(array_key_exists('file', $trace[2])){
                $caller .= ' at ' . $trace[2]["file"]
                        . ': ' . $trace[2]["line"];
            }
        }
        $now = pDateTime::microtime();
        $this->entries->add(new pProfileEntry($now, $call,
                memory_get_usage(true), $now - $time, $file, $caller));
        $time = $now;
    }
    
}