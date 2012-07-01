<?php
pload('IEventHandler');
pload('pObservableEvent');
pload('pEventObserver');

/**
 * pEventHandler class
 * 
 * Implementation of the event handler
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.event
 * @since 1.0-elenor
 */
class pEventHandler implements IEventHandler {
    
    /**
     * The events to the event listener
     * @var array
     * @since 1.0-elenor
     */
    private $events = array();
    
    /**
     * Bind an event listener to an event of the class
     * @param string $event The name of the event
     * @param IObserver|Closure|callback $listener The function, method or
     *              observer to listen to this event
     * @since 1.0-elenor
     */
    public function on($event, $listener){
        if(!in_array($event, $this->events)){
            $this->events[$event] = new pObservableEvent($this);
        }
        if(is_callable($listener)){
            $listener = new pEventObserver($handler);
        }
        $this->events[$event]->attach($listener);
    }
    
    /**
     * Trigger an event in this handler
     * @param string $event The name of the event to trigger
     * @param mixed $args (optional) Additional information to provide to the
     *              observers.
     * @since 1.0-elenor
     */
    public function trigger($event, $args = null){
        if(in_array($event, $this->events)){
            if(func_num_args() == 1){
                $this->events[$event]->notify($args);
            }else{
                $this->events[$event]->notify();
            }
        }
    }
    
}